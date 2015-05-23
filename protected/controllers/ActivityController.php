<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ActivityMovement;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\controllers\support\ActivityControllerSupport;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl;

/**
 * Description of ActivityController
 *
 * @author britech
 */
class ActivityController extends Controller {

    private $logger;
    private $modelLoaderUtil;
    private $controllerSupport;
    private $initiativeService;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->controllerSupport = ActivityControllerSupport::getInstance($this);
        $this->initiativeService = new InitiativeManagementServiceSimpleImpl();
    }

    public function index($initiative, $period) {
        $date = \DateTime::createFromFormat('Y-m-d', "{$period}-1");
        $data = $this->loadInitiativeModel($initiative);

        $this->title = ApplicationConstants::APP_NAME . ' - Activity Dashboard';
        $this->layout = 'column-2';
        $this->render('activity/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => 'active'
            ),
            'sidebar' => array(
                'file' => 'activity/_index-navi'
            ),
            'data' => $data,
            'date' => $date,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function manage($id, $period) {
        $activity = $this->loadModel($id);
        $initiative = $this->loadInitiativeModel(null, $activity);

        $this->title = ApplicationConstants::APP_NAME;
        $this->layout = "column-2";
        $this->render('activity/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => 'active'
            ),
            'sidebar' => array(
                'file' => 'activity/_manage-navi'
            ),
            'data' => $activity,
            'initiative' => $initiative,
            'period' => $period,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function updateStatus() {
        $this->validatePostData(array('id', 'status', 'period'), false);

        $id = $this->getFormData('id');
        $status = strtoupper($this->getFormData('status'));
        $period = $this->getFormData('period');

        $activity = $this->loadModel($id, true);
        $activity->activityEnvironmentStatus = $status;
        $activity->movements = array($this->controllerSupport->constructActivityMovementEntity($status));
        $initiative = $this->loadInitiativeModel(null, $activity, true);
        try {
            $this->initiativeService->updateActivity($activity, $this->modelLoaderUtil->loadComponentModel(null, $activity, array(ModelLoaderUtil::KEY_REMOTE => true)));
            $this->initiativeService->insertActivityMovement($activity);
            $this->logCustomRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Activity Status Update]\n\nActivity:\t{$activity->title}\nStatus:\t{$activity->translateStatusCode()}");
            $this->logEnlistedMovements($initiative, $activity->movements);
            $this->setSessionData('notif', array('class' => 'info', 'message' => "{$activity->title} set to {$activity->translateStatusCode($status)}"));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'An error occured during Activity status update'));
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('activity/index', 'initiative' => $initiative->id, 'period' => $period))));
    }

    public function enlistMovement($id, $period) {
        $activity = $this->loadModel($id);
        $initiative = $this->loadInitiativeModel(null, $activity);
        $movement = new ActivityMovement();
        $movement->periodDate = "{$period}-1";
        
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist Movement';
        $this->render('activity/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => array('activity/manage', 'id' => $activity->id, 'period' => $period),
                'Enlist Movement' => 'active'
            ),
            'model' => $movement,
            'activity' => $activity,
            'action' => array('activity/insertMovement'),
            'header' => 'Enlist Activity Movement',
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function validateMovementInput() {
        $this->validatePostData(array('ActivityMovement'), true);
        $activityMovement = $this->controllerSupport->constructActivityMovementEntity();
        $this->remoteValidateModel($activityMovement);
    }

    public function insertMovement() {
        $this->validatePostData(array('ActivityMovement', 'Activity'));

        $activityMovement = $this->controllerSupport->constructActivityMovementEntity();
        $activityData = $this->getFormData('Activity');
        $id = $activityData['id'];
        $activity = new Activity();
        $activity->id = $id;

        if (!$activityMovement->validate()) {
            $this->setSessionData('validation', $activityMovement->validationMessages);
            $this->redirect(array('activity/manage', 'id' => $activity->id));
            return;
        }

        $activity->movements = array($activityMovement);
        $initiative = $this->loadInitiativeModel(null, $activity);

        $this->initiativeService->insertActivityMovement($activity);
        $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $initiative->id, $activityMovement);
        $this->setSessionData('notif', array('class' => 'success', 'message' => "Activity Movement succesfully logged"));
        $this->redirect(array('activity/manage', 'id' => $activity->id, 'period'=>$activityMovement->periodDate->format('Y-m')));
    }

    public function finish($id = null, $period = null) {
        if (is_null($id) && is_null($period)) {
            $this->processFormInput();
        }
        $activity = $this->loadModel($id);
        $activity->activityEnvironmentStatus = Activity::STATUS_FINISHED;
        $initiative = $this->loadInitiativeModel(null, $activity);
        $this->title = ApplicationConstants::APP_NAME . ' - Finish Activity';
        $this->render('activity/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => array('activity/manage', 'id' => $activity->id, 'period' => $period),
                'Set Activity to Finished' => 'active'
            ),
            'model' => new ActivityMovement(),
            'activity' => $activity,
            'action' => array('activity/finish'),
            'header' => 'Set Activity to Finished',
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function stop($id = null, $period = null) {
        if (is_null($id) && is_null($period)) {
            $this->processFormInput();
        }

        $activity = $this->loadModel($id);
        $activity->activityEnvironmentStatus = Activity::STATUS_DROPPED;
        $initiative = $this->loadInitiativeModel(null, $activity);
        $this->title = ApplicationConstants::APP_NAME . ' - Discontinue Activity';
        $this->render('activity/enlist', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => array('activity/manage', 'id' => $activity->id, 'period' => $period),
                'Set Activity to Discontinued' => 'active'
            ),
            'model' => new ActivityMovement(),
            'activity' => $activity,
            'action' => array('activity/finish'),
            'header' => 'Set Activity to Discontinued',
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function movementLog($id, $period) {
        $activity = $this->loadModel($id);
        $initiative = $this->loadInitiativeModel(null, $activity);

        $this->title = ApplicationConstants::APP_NAME . ' - Movement Log';
        $this->layout = "column-2";
        $this->render('activity/log', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => array('activity/manage', 'id' => $activity->id, 'period' => $period),
                'Movement Log' => 'active'
            ),
            'sidebar' => array(
                'file' => 'activity/_log-navi'
            ),
            'id' => $activity->id
        ));
    }

    public function listMovements() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');
        $activity = $this->loadModel($id, true);
        $data = array();
        foreach ($activity->movements as $movement) {
            array_push($data, array(
                'date_entered' => $movement->movementTimestamp->format('M d, Y'),
                'user_entered' => $movement->retrieveName(),
                'output' => $movement->resolveOutputValue(),
                'amount' => $movement->resolveBudgetValue(),
                'notes' => $movement->constructNotes()
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function processFormInput() {
        $this->validatePostData(array('ActivityMovement', 'Activity'));

        $activityMovementEntry = $this->controllerSupport->constructActivityMovementEntity();
        if (!$activityMovementEntry->validate()) {
            $this->setSessionData('validation', $activityMovementEntry->validationMessages);
            $this->resolveFormRedirect();
        }

        $activityData = $this->getFormData('Activity');
        $activity = new Activity();
        $activity->bindValuesUsingArray(array(
            'activity' => $activityData
        ));
        $activityMovementStatus = $this->controllerSupport->constructActivityMovementEntity($activity->activityEnvironmentStatus);
        $activity->movements = array($activityMovementStatus, $activityMovementEntry);

        $model = $this->loadModel($activity->id);
        $model->activityEnvironmentStatus = $activity->activityEnvironmentStatus;
        $initiative = $this->loadInitiativeModel(null, $model);

        try {
            $this->initiativeService->updateActivity($model, $this->modelLoaderUtil->loadComponentModel(null, $model));
            $this->logCustomRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Activity Status Update]\n\nActivity:\t{$model->title}\nStatus:\t{$model->translateStatusCode()}");
            $this->initiativeService->insertActivityMovement($activity);
            $this->logEnlistedMovements($initiative, $activity->movements);
            $this->setSessionData('notif', array('class' => 'info', 'message' => "{$model->title} set to {$model->translateStatusCode()}"));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'An error occured while Activity Movement is being logged'));
        }
        $this->redirect(array('activity/index', 'initiative' => $initiative->id, 'period' => $model->startingPeriod->format('Y-m')));
    }

    private function resolveFormRedirect() {
        $activityData = $this->getFormData('Activity');
        $id = $activityData['id'];
        $status = $activityData['activityEnvironmentStatus'];
        if ($status == Activity::STATUS_FINISHED) {
            $this->redirect(array('activity/finish', 'id' => $id));
        } elseif ($status == Activity::STATUS_UNFINISHED) {
            /**
             * @todo handle unfinish redirect
             */
        }
        return;
    }

    private function logEnlistedMovements(Initiative $initiative, array $movements) {
        foreach ($movements as $movement) {
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $initiative->id, $movement);
        }
    }

    private function loadInitiativeModel($id = null, Activity $activity = null, $remote = false) {
        if (!is_null($id)) {
            $initiative = $this->modelLoaderUtil->loadInitiativeModel($id, null, array(ModelLoaderUtil::KEY_REMOTE => $remote));
        } elseif (!is_null($activity)) {
            $component = $this->modelLoaderUtil->loadComponentModel(null, $activity, array(ModelLoaderUtil::KEY_REMOTE => $remote));
            $phase = $this->modelLoaderUtil->loadPhaseModel(null, $component, array(ModelLoaderUtil::KEY_REMOTE => $remote));
            $initiative = $this->modelLoaderUtil->loadInitiativeModel(null, $phase, array(ModelLoaderUtil::KEY_REMOTE => $remote));
        } else {
            throw new ControllerException("No arguments defined");
        }
        $initiative->startingPeriod = $initiative->startingPeriod->format('Y-m-d');
        $initiative->endingPeriod = $initiative->endingPeriod->format('Y-m-d');
        return $initiative;
    }

    private function loadModel($id, $remote = false) {
        return $this->modelLoaderUtil->loadActivityModel($id, array(ModelLoaderUtil::KEY_REMOTE => $remote));
    }

}
