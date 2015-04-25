<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;
use org\csflu\isms\service\ubt\CommitmentManagementServiceSimpleImpl;

/**
 * Description of CommitmentController
 *
 * @author britech
 */
class CommitmentController extends Controller {

    private $ubtService;
    private $userService;
    private $commitmentService;
    private $commitmentModuleSupport;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->commitmentService = new CommitmentManagementServiceSimpleImpl();
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($this);
    }

    public function enlist() {
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist Commitments';
        $user = $this->commitmentModuleSupport->loadAccountModel();
        $this->render('commitment/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Enlist Commitments' => 'active'
            ),
            'model' => new Commitment(),
            'user' => $user,
            'wigSession' => $this->commitmentModuleSupport->loadOpenWigSession($user->employee->department),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('Commitment', 'WigSession', 'UserAccount'));

        $wigSessionData = $this->getFormData('WigSession');
        $userAccountData = $this->getFormData('UserAccount');
        $commitmentData = $this->getFormData('Commitment');

        $wigSession = new WigSession();
        $wigSession->bindValuesUsingArray(array('wigsession' => $wigSessionData), $wigSession);

        $commitmentList = explode('+', $commitmentData['commitment']);
        $commitments = array();
        foreach ($commitmentList as $commitmentEntry) {
            $commitment = new Commitment();
            $commitment->bindValuesUsingArray(array(
                'user' => $userAccountData,
                'commitment' => array('commitment' => $commitmentEntry)));
            $commitments = array_merge($commitments, array($commitment));
        }
        $wigSession->commitments = $commitments;

        if (count($wigSession->commitments) == 0) {
            $this->setSessionData('validation', array('Commitments should be defined'));
            $this->redirect(array('commitment/enlist'));
        }

        try {
            $commitmentsEnlisted = $this->commitmentService->insertCommitments($wigSession);
            $commitmentsToDisplay = array();
            foreach ($commitmentsEnlisted as $enlistedCommitment) {
                $commitmentsToDisplay = array_merge($commitmentsToDisplay, array($enlistedCommitment->commitment));
            }
            $this->setSessionData('notif', array('class' => 'success', 'message' => 'Commitment/s enlisted<br/>' . implode('<br/>', $commitmentsToDisplay)));
            $this->redirect(array('ip/index'));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), e);
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('commitment/enlist'));
        }
    }

    public function manage($id) {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Commitment';
        $this->layout = "column-2";
        $this->render('commitment/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Manage Commitment' => 'active'
            ),
            'data' => $this->loadModel($id),
            'sidebar' => array(
                'file' => 'commitment/_navigation'
            ),
            'model' => $this->loadModel($id),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function updateEntry($remote = 0) {
        $remoteIndicator = $remote == 0 ? false : true;
        $commitmentToUpdate = $this->validateCommitmentEntity($remoteIndicator);

        $this->commitmentModuleSupport->checkCommitmentAndUserIdentity($commitmentToUpdate, $remoteIndicator);

        $oldCommitment = $this->loadModel($commitmentToUpdate->id, $remoteIndicator);

        $url = array('ip/index');
        if ($commitmentToUpdate->computePropertyChanges($oldCommitment) > 0) {
            try {
                $this->commitmentService->updateCommitment($commitmentToUpdate);
                $this->setSessionData('notif', array('class' => 'info', 'message' => $this->resolveUpdateMessage($commitmentToUpdate)));
            } catch (ServiceException $ex) {
                $this->logger->warn($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
                $url = array('commitment/manage', 'id' => $commitmentToUpdate->id);
            }
        }

        if ($remote) {
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
        } else {
            $this->redirect($url);
        }
    }

    private function resolveUpdateMessage(Commitment $commitment) {
        if(in_array('commitmentEnvironmentStatus', $commitment->updatedFields)){
            $message = "{$commitment->commitment} is now set to {$commitment->translateStatusCode($commitment->commitmentEnvironmentStatus)}";
        } else {
            $message = "Commitment successfully updated";
        }
        return $message;
    }

    /**
     * @return Commitment
     */
    private function validateCommitmentEntity($remote = false) {
        $this->validatePostData(array('Commitment', 'UserAccount'));

        $userAccountData = $this->getFormData('UserAccount');
        $commitmentData = $this->getFormData('Commitment');

        $commitmentToUpdate = new Commitment();
        $commitmentToUpdate->bindValuesUsingArray(array(
            'user' => $userAccountData,
            'commitment' => $commitmentData
        ));

        if (!$commitmentToUpdate->validate()) {
            $this->setSessionData('validation', array($commitmentToUpdate->validationMessages));
            $url = array('commitment/manage', 'id' => $commitmentToUpdate->id);
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
            return;
        }
        return $commitmentToUpdate;
    }

    private function loadModel($id, $remote = false) {
        $commitment = $this->commitmentService->getCommitmentData($id);
        if (is_null($commitment->id)) {
            $url = array('ip/index');
            $this->setSessionData('notif', array('message' => 'No Commitment found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        return $commitment;
    }

}
