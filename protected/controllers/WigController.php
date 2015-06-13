<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\WigMeeting;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\controllers\support\WigSessionControllerSupport;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;
use org\csflu\isms\service\ubt\CommitmentManagementServiceSimpleImpl;

/**
 * Description of WigController
 *
 * @author britech
 */
class WigController extends Controller {

    private $ubtService;
    private $userService;
    private $commitmentService;
    private $controllerSupport;
    private $modelLoaderUtil;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->isRbacEnabled = true;
        $this->moduleCode = ModuleAction::MODULE_UBT;
        $this->actionCode = "WIGM";
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementService();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->commitmentService = new CommitmentManagementServiceSimpleImpl();
        $this->controllerSupport = WigSessionControllerSupport::getInstance($this);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
    }

    public function index($ubt) {
        $unitBreakthrough = $this->loadUbtModel($ubt);
        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Manage WIG Sessions';
        $this->render('wig/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => 'active'
            ),
            'model' => new WigSession(),
            'ubtModel' => $unitBreakthrough,
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function listMeetings() {
        $this->validatePostData(array('ubt'), true);
        $id = $this->getFormData('ubt');
        $unitBreakthrough = $this->loadUbtModel($id, null, true);
        $data = array();
        $pointer = 0;
        foreach ($unitBreakthrough->wigMeetings as $wigMeeting) {
            array_push($data, array(
                'number' => $pointer,
                'timeline' => "{$wigMeeting->startingPeriod->format('M. j')} - {$wigMeeting->endingPeriod->format('M. j')}",
                'status' => $wigMeeting->translateStatusCode(),
                'action' => $this->resolveActionLinks($wigMeeting),
                'id' => $wigMeeting->id,
                'description' => "Week #{$pointer} ({$wigMeeting->startingPeriod->format('M j')} - {$wigMeeting->endingPeriod->format('M j')})"
            ));
            $pointer++;
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function insert() {
        $this->validatePostData(array('WigSession', 'UnitBreakthrough'));

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $wigMeetingData = $this->getFormData('WigSession');

        $unitBreakthrough = $this->loadUbtModel($unitBreakthroughData['id']);
        $wigSession = new WigSession();
        $wigSession->bindValuesUsingArray(array('wigsession' => $wigMeetingData), $wigSession);

        if (!$wigSession->validate()) {
            $this->setSessionData('validation', $wigSession->validationMessages);
            $this->redirect(array('wig/index', 'ubt' => $unitBreakthrough->id));
        }

        try {
            $id = $this->ubtService->insertWigSession($wigSession, $unitBreakthrough);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession);
            $this->redirect(array('wig/view', 'id' => $id));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('wig/index', 'ubt' => $unitBreakthrough->id));
        }
    }

    public function view($id) {
        $wigSession = $this->loadModel($id);
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);

        $wigSession->startingPeriod = $wigSession->startingPeriod->format('Y-m-d');
        $wigSession->endingPeriod = $wigSession->endingPeriod->format('Y-m-d');
        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');

        $this->layout = "column-2";
        $this->title = ApplicationConstants::APP_NAME . ' - WIG Session Info';
        $this->render('wig/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => array('wig/index', 'ubt' => $unitBreakthrough->id),
                'WIG Session' => 'active'
            ),
            'sidebar' => array(
                'file' => 'wig/_view-navi'
            ),
            'data' => $wigSession,
            'tableData' => $this->controllerSupport->collateCommitments($wigSession),
            'accounts' => $this->controllerSupport->listEmployees(),
            'ubt' => $unitBreakthrough,
            'lm1' => $this->controllerSupport->retrieveAlignedLeadMeasure($wigSession, $unitBreakthrough->leadMeasures, LeadMeasure::DESIGNATION_1),
            'lm2' => $this->controllerSupport->retrieveAlignedLeadMeasure($wigSession, $unitBreakthrough->leadMeasures, LeadMeasure::DESIGNATION_2),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function listUbtMovements() {
        $this->validatePostData(array('wig'), true);

        $id = $this->getFormData('wig');
        $wigSession = $this->loadModel($id, null, true);
        $ubt = $this->loadUbtModel(null, $wigSession, true);

        $data = array();
        foreach ($wigSession->movementUpdates as $movementData) {
            array_push($data, array(
                'user'=> nl2br("{$movementData->getShortName()}\n{$movementData->dateEntered->format('M d, Y g:i:s A')}"),
                'ubt' => $this->controllerSupport->resolveUnitBreakthroughMovement($movementData, $ubt),
                'lm1' => $this->controllerSupport->resolveLeadMeasureMovements($wigSession, $ubt->leadMeasures, $movementData, LeadMeasure::DESIGNATION_1),
                'lm2' => $this->controllerSupport->resolveLeadMeasureMovements($wigSession, $ubt->leadMeasures, $movementData, LeadMeasure::DESIGNATION_2),
                'notes' => nl2br(implode("\n", explode('+', $movementData->notes)))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function update() {
        $this->validatePostData(array('WigSession'));

        $wigSessionData = $this->getFormData('WigSession');
        $wigSession = new WigSession();
        $wigSession->bindValuesUsingArray(array('wigsession' => $wigSessionData));
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);

        $oldWigSession = $this->loadModel($wigSession->id);
        if ($wigSession->computePropertyChanges($oldWigSession) > 0) {
            try {
                $this->ubtService->updateWigSession($wigSession);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession, $oldWigSession);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Timeline updated'));
            } catch (ServiceException $ex) {
                $this->logger->warn($ex->getMessage(), $ex);
                $this->setSessionData('notif', array('message' => $ex->getMessage()));
            }
        }
        $this->redirect(array('wig/view', 'id' => $wigSession->id));
    }

    public function delete() {
        $this->validatePostData(array('id'));

        $id = $this->getFormData('id');
        $wigSession = $this->loadModel($id, null, true);
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession, true);

        $this->ubtService->deleteWigSession($id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession);
        $this->setSessionData('notif', array('class' => 'error', 'message' => 'WIG Session deleted'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('wig/index', 'ubt' => $unitBreakthrough->id))));
    }

    public function listCommitments($wig, $emp) {
        $wigSession = $this->loadModel($wig);
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);
        $account = $this->loadAccountModel($emp);

        $this->title = ApplicationConstants::APP_NAME . ' - Commitment Details';
        $this->render('wig/commitments', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => array('wig/index', 'ubt' => $unitBreakthrough->id),
                'WIG Session' => array('wig/view', 'id' => $wigSession->id),
                'Commitments' => 'active'
            ),
            'account' => $account,
            'commitments' => $this->commitmentService->listCommitments($account, $wigSession)
        ));
    }

    public function movementLog($commitment) {
        $commitmentData = $this->loadCommitmentModel($commitment);
        $wigSession = $this->loadModel(null, $commitmentData);
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);

        $this->title = ApplicationConstants::APP_NAME . ' - Commitment Movement';
        $this->render('commitment/log', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => array('wig/index', 'ubt' => $unitBreakthrough->id),
                'WIG Session' => array('wig/view', 'id' => $wigSession->id),
                'Commitments' => array('wig/listCommitments', 'wig' => $wigSession->id, 'emp' => $commitmentData->id),
                'Movement Log' => 'active'
            ),
            'data' => $commitmentData
        ));
    }

    public function close($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('WigSession', 'WigMeeting', 'UnitBreakthroughMovement'));
            $this->closeWigSession();
        }
        $wigSession = $this->loadModel($id);
        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);

        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Close WIG Session';
        $this->render('wig/close', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => array('wig/index', 'ubt' => $unitBreakthrough->id),
                'WIG Session' => array('wig/view', 'id' => $wigSession->id),
                'Close' => 'active'
            ),
            'meetingModel' => new WigMeeting(),
            'movementModel' => new UnitBreakthroughMovement(),
            'ubtModel' => $unitBreakthrough,
            'sessionModel' => $wigSession,
            'collatedCommitments' => $this->controllerSupport->collateCommitments($wigSession),
            'accounts' => $this->controllerSupport->listEmployees(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function closeWigSession() {
        $wigSessionData = $this->getFormData('WigSession');
        $wigMeetingData = $this->getFormData('WigMeeting');

        $ubtMovement = $this->constructUbtMovementData();
        $wigSession = $this->loadModel($wigSessionData['id']);
        $wigSession->bindValuesUsingArray(array('wigMeeting' => $wigMeetingData));
        $wigSession->movementUpdates = array($ubtMovement);
        $wigSession->wigMeetingEnvironmentStatus = WigSession::STATUS_CLOSED;

        if (!($ubtMovement->validate() || $wigSession->wigMeeting->validate())) {
            $validationMessage = array_merge($wigSession->movementUpdate->validationMessages, $wigSession->wigMeeting->validationMessages);
            $this->setSessionData('validation', $validationMessage);
            $this->redirect(array('wig/close', 'id' => $wigSession->id));
        }

        $unitBreakthrough = $this->loadUbtModel(null, $wigSession);
        try {
            $this->ubtService->closeWigSession($wigSession);
            $this->logCustomRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession->getClosedWigSessionLogOutput());
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession->wigMeeting);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $ubtMovement);
            $this->setSessionData('notif', array('class' => 'info', 'message' => "WIG Session successfully closed"));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('notif', array('message' => $ex->getMessage()));
        }
        $this->redirect(array('wig/view', 'id' => $wigSession->id));
    }

    private function constructUbtMovementData() {
        $ubtMovementData = $this->getFormData('UnitBreakthroughMovement');
        $movementData = new UnitBreakthroughMovement();
        $movementData->bindValuesUsingArray(array(
            'unitbreakthroughmovement' => $ubtMovementData), $movementData);
        $movementData->user = $this->modelLoaderUtil->loadAccountModel();
        return $movementData;
    }

    public function validateWigClosureInput() {
        $this->validatePostData(array('WigMeeting', 'UnitBreakthroughMovement'), true);

        $wigMeetingData = $this->getFormData('WigMeeting');
        $ubtMovementData = $this->getFormData('UnitBreakthroughMovement');

        $wigMeeting = new WigMeeting();
        $wigMeeting->bindValuesUsingArray(array('wigmeeting' => $wigMeetingData));

        $ubtMovement = new UnitBreakthroughMovement();
        $ubtMovement->bindValuesUsingArray(array('unitbreakthroughmovement' => $ubtMovementData), $ubtMovement);

        $validationMessages = array();
        if (!($wigMeeting->validate() || $ubtMovement->validate())) {
            $validationMessages = array_merge($wigMeeting->validationMessages, $ubtMovement->validationMessages);
            $validationData = "";
            foreach ($validationMessages as $message) {
                $validationData.="{$message}\n";
            }
            $this->renderAjaxJsonResponse(array('message' => nl2br($validationData)));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    private function resolveActionLinks(WigSession $wigMeeting) {
        $links = array(ApplicationUtils::generateLink(array('wig/view', 'id' => $wigMeeting->id), 'View'));
        if ($wigMeeting->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN && count($wigMeeting->commitments) == 0 && is_null($wigMeeting->movementUpdate)) {
            array_push($links, ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$wigMeeting->id}")));
        }
        return implode('&nbsp;|&nbsp;', $links);
    }

    private function loadModel($id = null, Commitment $commitment = null, $remote = false) {
        return $this->modelLoaderUtil->loadWigSessionModel($id, $commitment, array('remote' => $remote));
    }

    private function loadAccountModel($id) {
        return $this->modelLoaderUtil->loadAccountModel($id);
    }

    private function loadUbtModel($id = null, WigSession $wigSession = null, $remote = false) {
        return $this->modelLoaderUtil->loadUnitBreakthroughModel($id, null, $wigSession, array('remote' => $remote));
    }

    private function loadCommitmentModel($id) {
        return $this->modelLoaderUtil->loadCommitmentModel($id);
    }

}
