<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;

/**
 * Description of UbtController
 *
 * @author britech
 */
class UbtController extends Controller {

    private $logger;
    private $mapService;
    private $scorecardService;
    private $departmentService;
    private $ubtService;
    private $userService;
    private $modelLoaderUtil;

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->departmentService = new DepartmentService();
        $this->ubtService = new UnitBreakthroughManagementService();
        $this->userService = new UserManagementService();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($map) {
        $strategyMap = $this->modelLoaderUtil->loadMapModel($map);

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

        $strategyMap = $this->modelLoaderUtil->loadMapModel($id);
        $unitBreakthroughs = $this->ubtService->listUnitBreakthrough($strategyMap);

        $data = array();
        foreach ($unitBreakthroughs as $unitBreakthrough) {
            array_push($data, array(
                'description' => $unitBreakthrough->description,
                'status' => UnitBreakthrough::translateUbtStatusCode($unitBreakthrough->unitBreakthroughEnvironmentStatus),
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
            $map = $this->loadMapModel(null, $unitBreakthrough);
            array_push($data, array(
                'id' => $unitBreakthrough->id,
                'description' => $unitBreakthrough->description,
                'status' => UnitBreakthrough::translateUbtStatusCode($unitBreakthrough->unitBreakthroughEnvironmentStatus),
                'map' => $map->name,
                'action' => $this->resolveActionLinks($unitBreakthrough)
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function resolveActionLinks(UnitBreakthrough $unitBreakthrough) {
        $links = array(ApplicationUtils::generateLink(array('ubt/viewMovements', 'id' => $unitBreakthrough->id), 'UBT Movements'));

        switch ($unitBreakthrough->unitBreakthroughEnvironmentStatus) {
            case UnitBreakthrough::STATUS_ACTIVE:
                $links = array_merge($links, array(
                    ApplicationUtils::generateLink(array('wig/index', 'ubt' => $unitBreakthrough->id), 'Manage WIG Sessions'),
                    ApplicationUtils::generateLink('#', 'Flag as Complete', array('id' => "complete-{$unitBreakthrough->id}")),
                    ApplicationUtils::generateLink('#', 'Flag as Inactive', array('id' => "inactivate-{$unitBreakthrough->id}"))
                ));
                break;

            case UnitBreakthrough::STATUS_INACTIVE:
                $links = array_merge($links, array(
                    ApplicationUtils::generateLink('#', 'Activate UBT', array('id' => "activate-{$unitBreakthrough->id}"))
                ));
                break;
        }

        return implode('&nbsp;|&nbsp;', $links);
    }

    public function create($map) {
        $strategyMap = $this->modelLoaderUtil->loadMapModel($map);
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
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function validateUbtInput() {
        $unitBreakthrough = new UnitBreakthrough();

        try {
            $this->validatePostData(array('UnitBreakthrough', 'Department', 'UnitOfMeasure', 'Objective', 'MeasureProfile'));

            $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
            $departmentData = $this->getFormData('Department');
            $uomData = $this->getFormData('UnitOfMeasure');
            $objectiveData = $this->getFormData('Objective');
            $measureProfileData = $this->getFormData('MeasureProfile');
            $unitBreakthrough->bindValuesUsingArray(array(
                'unit' => $departmentData,
                'uom' => $uomData,
                'objectives' => $objectiveData,
                'measures' => $measureProfileData,
                'unitbreakthrough' => $unitBreakthroughData
            ));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        if (!$unitBreakthrough->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $unitBreakthrough->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    public function insert() {
        $this->validatePostData(array('UnitBreakthrough', 'Objective', 'MeasureProfile', 'Department', 'UnitOfMeasure', 'StrategyMap'));

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');
        $departmentData = $this->getFormData('Department');
        $uomData = $this->getFormData('UnitOfMeasure');
        $strategyMapData = $this->getFormData('StrategyMap');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'objectives' => $objectiveData,
            'measures' => $measureProfileData,
            'unit' => $departmentData,
            'uom' => $uomData
        ));

        $strategyMap = $this->modelLoaderUtil->loadMapModel($strategyMapData['id']);

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
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel($id);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, null, null, null, $unitBreakthrough);

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
                        'Manage Lead Measures' => array('ubt/manageLeadMeasures', 'ubt' => $unitBreakthrough->id),
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
            $this->validatePostData(array('UnitBreakthrough', 'Department', 'StrategyMap'));
            $this->processUbtUpdate();
        }
        $unitBreakthrough = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);

        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');
        $this->render('ubt/form', array(
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
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function processUbtUpdate() {
        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $departmentData = $this->getFormData('Department');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'unit' => $departmentData
        ));
        $unitBreakthrough->validationMode = Model::VALIDATION_MODE_UPDATE;
        if (!$unitBreakthrough->validate()) {
            $this->setSessionData('validation', $unitBreakthrough->validationMessages);
            $this->redirect(array('ubt/update', 'id' => $unitBreakthrough->id));
        }
        $this->logger->debug($unitBreakthrough);
        $oldModel = clone $this->loadModel($unitBreakthrough->id);
        if ($unitBreakthrough->computePropertyChanges($oldModel) > 0) {
            $strategyMapData = $this->getFormData('StrategyMap');
            $strategyMap = $this->loadMapModel($strategyMapData['id']);
            try {
                $this->ubtService->updateUnitBreakthrough($unitBreakthrough, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $unitBreakthrough, $oldModel);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Unit Breakthrough updated'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('ubt/update', 'id' => $unitBreakthrough->id));
            }
        }
        $this->redirect(array('ubt/view', 'id' => $unitBreakthrough->id));
    }

    public function manageLeadMeasures($ubt) {
        $unitBreakthrough = $this->loadModel($ubt);
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Measures';
        $this->render('ubt/lead-measures', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Manage Lead Measures' => 'active'),
            'model' => new LeadMeasure,
            'ubtModel' => $unitBreakthrough,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function listLeadMeasures() {
        $this->validatePostData(array('ubt'));
        $id = $this->getFormData('ubt');

        $unitBreakthrough = $this->loadModel($id);
        $data = array();
        foreach ($unitBreakthrough->leadMeasures as $leadMeasure) {
            array_push($data, array(
                'description' => $leadMeasure->description,
                'status' => LeadMeasure::translateEnvironmentStatus($leadMeasure->leadMeasureEnvironmentStatus),
                'actions' => $this->resolveLeadMeasureActionLinks($leadMeasure)
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function resolveLeadMeasureActionLinks(LeadMeasure $leadMeasure) {
        $links = array(ApplicationUtils::generateLink(array('ubt/updateLeadMeasure', 'id' => $leadMeasure->id), 'Update'));
        if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE) {
            $links = array_merge($links, array(ApplicationUtils::generateLink('#', 'Disable', array('id' => "disable-{$leadMeasure->id}"))));
        } elseif ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_INACTIVE) {
            $links = array_merge($links, array(ApplicationUtils::generateLink('#', 'Enable', array('id' => "enable-{$leadMeasure->id}"))));
        }
        return implode('&nbsp;|&nbsp;', $links);
    }

    public function insertLeadMeasures() {
        $this->validatePostData(array('LeadMeasure', 'UnitBreakthrough'));

        $leadMeasureData = $this->getFormData('LeadMeasure');
        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'leadMeasures' => $leadMeasureData
        ));
        if (count($unitBreakthrough->leadMeasures) == 0) {
            $this->setSessionData('validation', array('Lead Measures should be defined'));
        } else {
            try {
                $unitBreakthrough->leadMeasures = $this->ubtService->insertLeadMeasures($unitBreakthrough);
                $this->logLinkedRecords($unitBreakthrough);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Lead Measure/s added'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        }
        $this->redirect(array('ubt/manageLeadMeasures', 'ubt' => $unitBreakthrough->id));
    }

    public function updateLeadMeasure($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('LeadMeasure'));
            $this->processLeadMeasureUpdate();
        }
        $leadMeasure = $this->loadLeadMeasureModel($id);
        $unitBreakthrough = $this->loadModel(null, $leadMeasure);
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Measures';
        $this->render('ubt/lead-measures', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Manage Lead Measures' => array('ubt/manageLeadMeasures', 'ubt' => $unitBreakthrough->id),
                'Update' => 'active'),
            'model' => $leadMeasure,
            'ubtModel' => $unitBreakthrough,
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
    }

    private function processLeadMeasureUpdate() {
        $leadMeasureData = $this->getFormData('LeadMeasure');
        $leadMeasure = new LeadMeasure();
        $leadMeasure->bindValuesUsingArray(array('leadmeasure' => $leadMeasureData), $leadMeasure);
        $oldLeadMeasure = $this->loadLeadMeasureModel($leadMeasure->id);

        if ($leadMeasure->computePropertyChanges($oldLeadMeasure) > 0 && $leadMeasure->validate()) {
            try {
                $this->ubtService->updateLeadMeasure($leadMeasure);
                $unitBreakthrough = $this->loadModel(null, $leadMeasure);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $leadMeasure, $oldLeadMeasure);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Lead Measure updated'));
                $this->redirect(array('ubt/manageLeadMeasures', 'ubt' => $unitBreakthrough->id));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('ubt/updateLeadMeasure', 'id' => $oldLeadMeasure->id));
            }
        } elseif ($leadMeasure->computePropertyChanges($oldLeadMeasure) > 0 && !$leadMeasure->validate()) {
            $this->setSessionData('validation', $leadMeasure->validationMessages);
            $this->redirect(array('ubt/updateLeadMeasure', 'id' => $oldLeadMeasure->id));
        } elseif ($leadMeasure->computePropertyChanges($oldLeadMeasure) == 0) {
            $this->logger->debug('no changes');
            $this->redirect(array('ubt/updateLeadMeasure', 'id' => $oldLeadMeasure->id));
        }
    }

    public function updateLeadMeasureStatus() {
        try {
            $this->validatePostData(array('lm', 'status'));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
            return;
        }
        $id = $this->getFormData('lm');
        $status = $this->getFormData('status');

        $leadMeasure = $this->loadLeadMeasureModel($id, true);
        $leadMeasure->leadMeasureEnvironmentStatus = $status;

        $oldLeadMeasure = $this->loadLeadMeasureModel($id, true);
        $unitBreakthrough = $this->loadModel(null, $leadMeasure);
        if ($leadMeasure->computePropertyChanges($oldLeadMeasure) > 0) {
            try {
                $this->ubtService->updateLeadMeasureStatus($leadMeasure);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $leadMeasure, $oldLeadMeasure);
                $this->setSessionData('notif', array('class' => 'info', 'message' => "Lead Measure - {$leadMeasure->description} is now set to {$leadMeasure->translateEnvironmentStatus($leadMeasure->leadMeasureEnvironmentStatus)}"));
            } catch (ServiceException $ex) {
                $this->setSessionData('notif', array('class' => 'error', 'message' => $ex->getMessage()));
                $this->logger->warn($ex->getMessage(), $ex);
            }
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('ubt/manageLeadMeasures', 'ubt' => $unitBreakthrough->id))));
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

    private function loadLeadMeasureModel($id, $remote = false) {
        $leadMeasure = $this->ubtService->retrieveLeadMeasure($id);
        if (is_null($leadMeasure->id)) {
            $this->setSessionData('notif', array('message' => 'Lead Measure not found'));
            $url = array('map/index');
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        $leadMeasure->validationMode = Model::VALIDATION_MODE_UPDATE;
        return $leadMeasure;
    }

    private function loadModel($id = null, LeadMeasure $leadMeasure = null, $remote = false) {
        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($id, $leadMeasure);
        if (is_null($unitBreakthrough->id)) {
            $this->setSessionData('notif', array('message' => 'Unit Breakthrough not found'));
            $url = array('map/index');
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        $unitBreakthrough->validationMode = Model::VALIDATION_MODE_UPDATE;
        return $unitBreakthrough;
    }

    private function loadMapModel($id = null, UnitBreakthrough $unitBreakthrough = null) {
        $strategyMap = $this->mapService->getStrategyMap($id, null, null, null, null, $unitBreakthrough);
        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        return $strategyMap;
    }

}
