<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\Model;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\ubt\Commitment;
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
                'status' => $wigMeeting->translateWigMeetingEnvironmentStatus(),
                'action' => $this->resolveActionLinks($wigMeeting)
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
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
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
