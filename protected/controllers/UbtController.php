<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\controllers\support\UnitBreakthroughControllerSupport;
use org\csflu\isms\controllers\support\WigSessionControllerSupport;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;

/**
 * Description of UbtController
 *
 * @author britech
 */
class UbtController extends Controller {

    private $logger;
    private $ubtService;
    private $modelLoaderUtil;
    private $controllerSupport;
    private $wigControllerSupport;

    public function __construct() {
        $this->checkAuthorization();
        $this->ubtService = new UnitBreakthroughManagementService();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->controllerSupport = UnitBreakthroughControllerSupport::getInstance($this);
        $this->wigControllerSupport = WigSessionControllerSupport::getInstance($this);
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($map) {
        $strategyMap = $this->loadMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage UBTs';
        $this->layout = "column-2";
        $this->render('ubt/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Action',
                    'links' => array(
                        'Add Unit Breakthrough' => array('ubt/create', 'map' => $strategyMap->id)))),
            'map' => $strategyMap->id
        ));
    }

    public function manage() {
        $userAccount = $this->modelLoaderUtil->loadAccountModel();

        $this->title = ApplicationConstants::APP_NAME . 'Manage Unit Breakthroughs';
        $this->render('ubt/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => 'active'
            ),
            'unit' => $userAccount->employee->department->id,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function listUnitBreakthroughsByStrategyMap() {
        $this->validatePostData(array('map'));
        $id = $this->getFormData('map');

        $strategyMap = $this->loadMapModel($id);
        $unitBreakthroughs = $this->ubtService->listUnitBreakthrough($strategyMap);

        $data = array();
        foreach ($unitBreakthroughs as $unitBreakthrough) {
            array_push($data, array(
                'description' => $unitBreakthrough->description,
                'status' => $unitBreakthrough->translateUbtStatusCode(),
                'unit' => $unitBreakthrough->unit->name,
                'action' => ApplicationUtils::generateLink(array('ubt/view', 'id' => $unitBreakthrough->id), 'View')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function listUnitBreakthroughsByDepartment() {
        $this->validatePostData(array('department'));
        $id = $this->getFormData('department');

        $department = $this->modelLoaderUtil->loadDepartmentModel($id);
        $unitBreakthroughs = $this->ubtService->listUnitBreakthrough(null, $department);

        $data = array();
        foreach ($unitBreakthroughs as $unitBreakthrough) {
            $map = $this->modelLoaderUtil->loadMapModel(null, null, null, null, null, $unitBreakthrough);
            array_push($data, array(
                'id' => $unitBreakthrough->id,
                'description' => strval($unitBreakthrough->description),
                'status' => $unitBreakthrough->translateUbtStatusCode(),
                'map' => $map->name,
                'action' => ApplicationUtils::generateLink(array('ubt/movements', 'id' => $unitBreakthrough->id), 'UBT Movements') . '&nbsp;|&nbsp;' . ApplicationUtils::generateLink(array('wig/index', 'ubt' => $unitBreakthrough->id), 'Manage WIG Sessions')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create($map) {
        $strategyMap = $this->loadMapModel($map);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');

        $this->title = ApplicationConstants::APP_NAME . ' - Create a UBT';
        $this->render('ubt/form', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Create UBT' => 'active'),
            'model' => new UnitBreakthrough(),
            'uomModel' => new UnitOfMeasure(),
            'objectiveModel' => new Objective(),
            'measureProfileModel' => new MeasureProfile(),
            'departmentModel' => new Department(),
            'mapModel' => $strategyMap,
            'statusList' => UnitBreakthrough::listUbtStatusCodes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function validateUbtInput() {
        $this->validatePostData(array('UnitBreakthrough', 'Department', 'UnitOfMeasure', 'Objective', 'MeasureProfile'), true);
        $unitBreakthrough = $this->controllerSupport->constructEnlistmentInputData();
        $this->remoteValidateModel($unitBreakthrough);
    }

    public function insert() {
        $this->validatePostData(array('UnitBreakthrough', 'Objective', 'MeasureProfile', 'Department', 'UnitOfMeasure', 'StrategyMap'));

        $strategyMapData = $this->getFormData('StrategyMap');
        $unitBreakthrough = $this->controllerSupport->constructEnlistmentInputData();

        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        if (!$unitBreakthrough->validate()) {
            $this->setSessionData('validation', $unitBreakthrough->validationMessages);
            $this->redirect(array('ubt/create', 'map' => $strategyMap->id));
        } else {
            $purifiedUnitBreakthrough = $this->purifyUbtInput($unitBreakthrough);
            try {
                $id = $this->ubtService->insertUnitBreakthrough($purifiedUnitBreakthrough, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $id, $purifiedUnitBreakthrough);
                $this->logLinkedRecords($purifiedUnitBreakthrough);
                $this->redirect(array('ubt/view', 'id' => $id));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('ubt/create', 'map' => $strategyMap->id));
            }
        }
    }

    public function view($id) {
        $unitBreakthrough = $this->loadUbtModel($id);
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . ' - About Unit Breakthrough';
        $this->layout = "column-2";
        $this->render('ubt/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'About Unit Breakthrough' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Entry Data' => array('ubt/update', 'id' => $unitBreakthrough->id),
                        'Manage Lead Measures' => array('leadMeasure/index', 'ubt' => $unitBreakthrough->id),
                        'Manage Strategy Alignments' => array('alignment/manageUnitBreakthrough', 'id' => $unitBreakthrough->id),
                    )
                )
            ),
            'notif' => $this->getSessionData('notif'),
            'data' => $unitBreakthrough
        ));
        $this->unsetSessionData('notif');
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('UnitBreakthrough', 'Department', 'StrategyMap', 'UnitOfMeasure'));
            $this->processUbtUpdate();
        }

        $unitBreakthrough = $this->loadUbtModel($id);
        $unitBreakthrough->validationMode = Model::VALIDATION_MODE_UPDATE;
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');

        $this->render('ubt/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'About Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Update UBT' => 'active'),
            'model' => $unitBreakthrough,
            'departmentModel' => $unitBreakthrough->unit,
            'mapModel' => $strategyMap,
            'statusList' => UnitBreakthrough::listUbtStatusCodes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function movements($id) {
        $ubt = $this->loadUbtModel($id);

        $this->title = ApplicationConstants::APP_NAME . ' - UBT Movements';
        $this->layout = 'column-2';
        $this->render('ubt/movements', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'UBT Movements' => 'active'
            ),
            'sidebar' => array(
                'file' => 'ubt/_movement-navi'
            ),
            'data' => $ubt,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function listUbtMovements() {
        $this->validatePostData(array('ubt'));
        $id = $this->getFormData('ubt');

        $ubt = $this->loadUbtModel($id);
        $data = array();
        $weekNumber = 0;
        foreach ($ubt->wigMeetings as $wigSession) {
            foreach ($wigSession->movementUpdates as $movementData) {
                array_push($data, array(
                    'user' => nl2br("{$movementData->getShortName()}\n{$movementData->dateEntered->format('M d, Y g:i:s A')}"),
                    'ubt' => $this->wigControllerSupport->resolveUnitBreakthroughMovement($movementData, $ubt),
                    'lm1' => $this->wigControllerSupport->resolveLeadMeasureMovements($wigSession, $ubt->leadMeasures, $movementData, LeadMeasure::DESIGNATION_1),
                    'lm2' => $this->wigControllerSupport->resolveLeadMeasureMovements($wigSession, $ubt->leadMeasures, $movementData, LeadMeasure::DESIGNATION_2),
                    'notes' => nl2br(implode("\n", explode('+', $movementData->notes))),
                    'wig' => "Week #{$weekNumber} ({$wigSession->startingPeriod->format('M d, Y')} - {$wigSession->endingPeriod->format('M d, Y')})"
                ));
            }
            $weekNumber++;
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function addMovement($id = null) {
        if (is_null($id)) {
            $this->enlistMovement();
        }
        $ubt = $this->loadUbtModel($id);
        $this->title = ApplicationConstants::APP_NAME . ' - Record UBT Movement';
        $this->render('ubt/add-movement', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'UBT Movements' => array('ubt/movements', 'id' => $ubt->id),
                'Add UBT Movement' => 'active'
            ),
            'model' => new UnitBreakthroughMovement(),
            'sessionModel' => new WigSession(),
            'ubtModel' => $ubt,
            'validation' => $this->getSessionData('validation'),
        ));
        $this->unsetSessionData('validation');
    }

    private function enlistMovement() {
        $this->validatePostData(array('WigSession', 'UnitBreakthroughMovement'));

        $wigSession = $this->controllerSupport->constructMovementData();
        $ubt = $this->loadUbtModel(null, $wigSession);

        if (!$wigSession->movementUpdates[0]->validate()) {
            $this->setSessionData('validation', $wigSession->movementUpdates[0]->validationMessages);
            $this->redirect(array('ubt/addMovement', 'id' => $ubt->id));
        }

        $this->ubtService->recordUbtMovement($wigSession);
        $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $ubt->id, $wigSession->movementUpdates[0]);
        $this->setSessionData('notif', array('class' => 'success', 'message' => 'UBT Movement added'));
        $this->redirect(array('ubt/movements', 'id' => $ubt->id));
    }

    public function validateMovementInput() {
        $this->validatePostData(array('WigSession', 'UnitBreakthroughMovement'), true);

        $wigSession = $this->controllerSupport->constructMovementData();

        $validationData = array();
        if (strlen($wigSession->id) < 1) {
            $validationData = array_merge($validationData, array('- WIG Session should be defined'));
        }

        if (!$wigSession->movementUpdates[0]->validate()) {
            $validationData = array_merge($validationData, $wigSession->movementUpdates[0]->validationMessages);
        }

        if (count($validationData) > 0) {
            $this->renderAjaxJsonResponse(array('message' => nl2br(implode("\n", $validationData))));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    private function processUbtUpdate() {
        $unitBreakthrough = $this->controllerSupport->constructUpdateInputData();

        if (!$unitBreakthrough->validate()) {
            $this->setSessionData('validation', $unitBreakthrough->validationMessages);
            $this->redirect(array('ubt/update', 'id' => $unitBreakthrough->id));
        }
        $oldModel = $this->modelLoaderUtil->loadUnitBreakthroughModel($unitBreakthrough->id);

        if ($unitBreakthrough->computePropertyChanges($oldModel) > 0) {
            $strategyMapData = $this->getFormData('StrategyMap');
            $strategyMap = $this->modelLoaderUtil->loadMapModel($strategyMapData['id']);
            try {
                $this->ubtService->updateUnitBreakthrough($unitBreakthrough, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $unitBreakthrough, $oldModel);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Unit Breakthrough updated'));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('ubt/update', 'id' => $unitBreakthrough->id));
            }
        }
        $this->redirect(array('ubt/view', 'id' => $unitBreakthrough->id));
    }

    private function purifyUbtInput(UnitBreakthrough $unitBreakthrough) {
        //purify objectives
        if (count($unitBreakthrough->objectives) > 0) {
            $objectives = array();
            foreach ($unitBreakthrough->objectives as $objective) {
                array_push($objectives, $this->modelLoaderUtil->loadObjectiveModel($objective->id));
            }
            $unitBreakthrough->objectives = $objectives;
        }

        //purify measure profiles
        if (count($unitBreakthrough->measures) > 0) {
            $indicators = array();
            foreach ($unitBreakthrough->measures as $measure) {
                array_push($indicators, $this->modelLoaderUtil->loadMeasureProfileModel($measure->id));
            }
            $unitBreakthrough->measures = $indicators;
        }

        //purify the department entity
        $unitBreakthrough->unit = $this->modelLoaderUtil->loadDepartmentModel($unitBreakthrough->unit->id);

        //purify the uom
        $unitBreakthrough->uom = $this->modelLoaderUtil->loadUomModel($unitBreakthrough->uom->id, array('url' => array('map/index')));
        return $unitBreakthrough;
    }

    private function logLinkedRecords(UnitBreakthrough $unitBreakthrough) {
        //log the linked objectives
        if (count($unitBreakthrough->objectives) > 0) {
            foreach ($unitBreakthrough->objectives as $objective) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, "[Objective linked]\n\nObjective:\t{$objective->description}");
            }
        }

        //log the linked measure profiles
        if (count($unitBreakthrough->measures) > 0) {
            foreach ($unitBreakthrough->measures as $measure) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, "[MeasureProfile linked]\n\nMeasure Profile:\t{$measure->indicator->description}");
            }
        }
    }

    private function loadMapModel($id = null, UnitBreakthrough $unitBreakthrough = null, $remote = false) {
        return $this->modelLoaderUtil->loadMapModel($id, null, null, null, null, $unitBreakthrough, array(ModelLoaderUtil::KEY_REMOTE => $remote));
    }

    private function loadUbtModel($id = null, WigSession $wigSession = null, $remote = false) {
        return $this->modelLoaderUtil->loadUnitBreakthroughModel($id, null, $wigSession, array(ModelLoaderUtil::KEY_REMOTE => $remote));
    }

}
