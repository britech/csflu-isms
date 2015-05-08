<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\CommitmentMovement;
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
        $wigSession = $this->commitmentModuleSupport->loadOpenWigSession($user->employee->department);

        if (strlen($wigSession->id) < 1) {
            $this->logger->warn("No open WIG Sessions found");
            $this->redirect(array('ip/index'));
        }

        $this->render('commitment/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Enlist Commitments' => 'active'
            ),
            'model' => new Commitment(),
            'user' => $user,
            'wigSession' => $wigSession,
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
        $commitment = $this->loadModel($id);

        $this->layout = "column-2";
        $this->render('commitment/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Manage Commitment' => 'active'
            ),
            'data' => $commitment,
            'sidebar' => array(
                'file' => 'commitment/_navigation'
            ),
            'model' => $commitment,
            'movementModel' => new CommitmentMovement(),
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

    public function deleteEntry() {
        $this->validatePostData(array('id'));

        $id = $this->getFormData('id');
        $commitment = $this->loadModel($id);
        $this->commitmentService->deleteCommitment($id);
        $this->setSessionData('notif', array('class' => 'error', 'message' => "{$commitment->commitment} deleted from your declared commitments"));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('ip/index'))));
    }

    public function validateCommitmentMovement() {
        try {
            $this->validatePostData(array('CommitmentMovement'));
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        }

        $commitmentMovementData = $this->getFormData('CommitmentMovement');

        $commitmentMovement = new CommitmentMovement();
        $commitmentMovement->bindValuesUsingArray(array('commitmentmovement' => $commitmentMovementData), $commitmentMovement);

        $this->remoteValidateModel($commitmentMovement);
    }

    public function insertMovement($isFinished = 0) {
        $finishIndicator = $isFinished == 1 ? true : false;
        $this->validatePostData(array('Commitment', 'CommitmentMovement'));

        $commitmentData = $this->getFormData('Commitment');
        $commitmentMovementData = $this->getFormData('CommitmentMovement');

        $commitmentMovement = new CommitmentMovement();
        $commitmentMovement->bindValuesUsingArray(array('commitmentmovement' => $commitmentMovementData), $commitmentMovement);

        $commitment = $this->loadModel($commitmentData['id']);
        $commitment->bindValuesUsingArray(array('commitment' => $commitmentData));
        $this->logger->debug($commitment);

        if (!$commitmentMovement->validate()) {
            $this->setSessionData('validation', $commitmentMovement->validationMessages);
            if ($finishIndicator) {
                $url = array('commitment/finish', 'id' => $commitment->id);
            } else {
                $url = array('commitment/manage', 'id' => $commitment->id);
            }
            $this->redirect($url);
            return;
        }

        $commitment->commitmentMovements = array($commitmentMovement);
        $this->commitmentService->addMovementUpdates($commitment);
        if ($finishIndicator) {
            $class = "info";
            $message = "{$commitment->commitment} is now set to {$commitment->translateStatusCode($commitment->commitmentEnvironmentStatus)}";
        } else {
            $class = "success";
            $message = "Movemement successfully added to Commitment {$commitment->commitment}";
        }
        $this->setSessionData('notif', array('class' => $class, 'message' => $message));
        $this->redirect(array('ip/index'));
    }

    public function finish($id) {
        $commitment = $this->loadModel($id);
        $commitment->commitmentEnvironmentStatus = Commitment::STATUS_FINISHED;

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Commitment Data';
        $this->render('commitment/finish', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Manage Commitment' => array('commitment/manage', 'id' => $commitment->id),
                'Set Commitment to Finished' => 'active'
            ),
            'model' => $commitment,
            'movementModel' => new CommitmentMovement(),
            'finishIndicator' => 1,
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function movementLog($commitment) {
        $data = $this->loadModel($commitment);
        $this->title = ApplicationConstants::APP_NAME . ' - Movements Log';
        $this->render('commitment/log', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Manage Commitment' => array('commitment/manage', 'id' => $data->id),
                'Movements Log' => 'active'
            ),
            'data' => $data
        ));
    }

    public function listCommitments() {
        $this->validatePostData(array('commitment'));

        $id = $this->getFormData('commitment');
        $commitment = $this->loadModel($id);
        $data = array();
        foreach ($commitment->commitmentMovements as $movement) {
            array_push($data, array(
                'figure' => $movement->movementFigure,
                'notes' => nl2br(implode("\n", explode("+", $movement->notes))),
                'date_entered' => $movement->dateCaptured->format('M d, Y h:i:s A')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function resolveUpdateMessage(Commitment $commitment) {
        if (in_array('commitmentEnvironmentStatus', $commitment->updatedFields)) {
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
