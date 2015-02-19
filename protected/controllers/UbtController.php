<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;

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

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->departmentService = new DepartmentService();
        $this->ubtService = new UnitBreakthroughManagementService();
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
                        'Add Unit Breakthrough' => array('ubt/create', 'map' => $strategyMap->id))))
        ));
    }

    public function create($map) {
        $strategyMap = $this->loadMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Create a UBT';
        $this->render('ubt/form', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Create UBT' => 'active'),
            'model' => new UnitBreakthrough(),
            'leadMeasureModel' => new LeadMeasure(),
            'objectiveModel' => new Objective(),
            'measureProfileModel' => new MeasureProfile(),
            'departmentModel' => new Department(),
            'mapModel' => $strategyMap,
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function validateUbtInput() {
        try {
            $this->validatePostData(array('UnitBreakthrough', 'LeadMeasure', 'Objective', 'MeasureProfile', 'Department'));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $leadMeasureData = $this->getFormData('LeadMeasure');
        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');
        $departmentData = $this->getFormData('Department');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'objectives' => $objectiveData,
            'indicators' => $measureProfileData,
            'unit' => $departmentData,
            'leadMeasures' => $leadMeasureData
        ));

        if (!$unitBreakthrough->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $unitBreakthrough->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    public function insert() {
        $this->validatePostData(array('UnitBreakthrough', 'LeadMeasure', 'Objective', 'MeasureProfile', 'Department', 'StrategyMap'));

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $leadMeasureData = $this->getFormData('LeadMeasure');
        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');
        $departmentData = $this->getFormData('Department');
        $strategyMapData = $this->getFormData('StrategyMap');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'leadMeasures' => $leadMeasureData,
            'objectives' => $objectiveData,
            'indicators' => $measureProfileData,
            'unit' => $departmentData
        ));

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
        $unitBreakthrough = $this->loadModel($id);
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

    private function purifyUbtInput(UnitBreakthrough $unitBreakthrough) {
        //purify objectives
        if (count($unitBreakthrough->objectives) > 0) {
            $objectives = array();
            foreach ($unitBreakthrough->objectives as $objective) {
                array_push($objectives, $this->mapService->getObjective($objective->id));
            }
            $unitBreakthrough->objectives = $objectives;
        }

        //purify measure profiles
        if (count($unitBreakthrough->measures) > 0) {
            $indicators = array();
            foreach ($unitBreakthrough->measures as $measure) {
                array_push($indicators, $this->scorecardService->getMeasureProfile($measure->id));
            }
            $unitBreakthrough->measures = $indicators;
        }

        //purify the department entity
        $unitBreakthrough->unit = $this->departmentService->getDepartmentDetail(array('id' => $unitBreakthrough->unit->id));
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

    private function loadModel($id, $remote = false) {
        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($id);
        if (is_null($unitBreakthrough->id)) {
            $this->setSessionData('notif', array('message' => 'Unit Breakthrough not found'));
            $url = array('map/index');
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
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
