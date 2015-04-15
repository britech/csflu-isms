<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\ubt\WigSession;
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
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->commitmentService = new CommitmentManagementServiceSimpleImpl();
    }

    public function enlist() {
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist Commitments';
        $user = $this->loadAccountModel();
        $this->render('commitment/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Enlist Commitments' => 'active'
            ),
            'model' => new Commitment(),
            'user' => $user,
            'wigSession' => $this->loadWigSession($user->employee->department),
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
            $commitment->bindValuesUsingArray(array('user' => $userAccountData, 
                'commitment' => array('commitment' => $commitmentEntry)));
            $commitments = array_merge($commitments, array($commitment));
        }
        $wigSession->commitments = $commitments;
        
        if(count($wigSession->commitments) == 0){
            $this->setSessionData('validation', array('Commitments should be defined'));
            $this->redirect(array('commitment/enlist'));
        }
        
        try {
            $commitmentsEnlisted = $this->commitmentService->insertCommitments($wigSession);
            $commitmentsToDisplay = array();
            foreach($commitmentsEnlisted as $enlistedCommitment){
                $commitmentsToDisplay = array_merge($commitmentsToDisplay, array($enlistedCommitment->commitment));
            }
            $this->setSessionData('notif', array('class'=>'success', 'message'=>'Commitment/s enlisted<br/>' . implode('<br/>', $commitmentsToDisplay)));
            $this->redirect(array('ip/index'));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), e);
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('commitment/enlist'));
        }
    }

    private function loadAccountModel($remote = false) {
        $account = $this->userService->getAccountById($this->getSessionData('user'));
        if (is_null($account->id)) {
            $url = array('site/logout');
            $this->logger->warn("User {$this->getSessionData('user')} was not found. Forcing log-out mechanism");
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        return $account;
    }

    private function loadWigSession(Department $department, $remote = false) {
        $unitBreakthroughs = $this->ubtService->listUnitBreakthrough(null, $department, true);
        if (count($unitBreakthroughs) == 0) {
            $url = array('ubt/manage');
            $this->setSessionData('notif', array('message' => 'No active UnitBreakthroughs defined'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }

        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($unitBreakthroughs[0]->id);
        foreach ($unitBreakthrough->wigMeetings as $wigSession) {
            if ($wigSession->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN) {
                return $wigSession;
            }
        }

        $this->setSessionData('notif', array('class' => 'error', 'message' => "No open WigSession found for {$unitBreakthrough->description}"));
        if ($remote) {
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
        } else {
            $this->redirect($url);
        }
    }

}
