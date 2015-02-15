<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;

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

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->departmentService = new DepartmentService();
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
            'objectiveModel' => new Objective(),
            'measureProfileModel' => new MeasureProfile(),
            'departmentModel' => new Department(),
            'mapModel' => $strategyMap
        ));
    }

    public function validateUbtInput() {
        try {
            $this->validatePostData(array('UnitBreakthrough', 'Objective', 'MeasureProfile'));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'objectives' => $objectiveData,
            'indicators' => $measureProfileData
        ));

        if (!$unitBreakthrough->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $unitBreakthrough->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    public function insert() {
        $this->validatePostData(array('UnitBreakthrough', 'Objective', 'MeasureProfile', 'Department', 'StrategyMap'));

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');
        $departmentData = $this->getFormData('Department');
        $strategyMapData = $this->getFormData('StrategyMap');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'objectives' => $objectiveData,
            'indicators' => $measureProfileData
        ));

        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        if (!$unitBreakthrough->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $unitBreakthrough->validationMessages));
            $this->redirect(array('ubt/create', 'map' => $strategyMap->id));
        } else {
            $purifiedUnitBreakthrough = $this->purifyUbtInput($unitBreakthrough);
            $department = $this->departmentService->getDepartmentDetail(array('id' => $departmentData['id']));
            $this->logger->debug($purifiedUnitBreakthrough);
            $this->logger->debug($department);
        }
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
        return $unitBreakthrough;
    }

    private function loadMapModel($id) {
        $strategyMap = $this->mapService->getStrategyMap($id);
        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('message' => 'Map not found'));
            $this->redirect(array('map/index'));
        }
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        return $strategyMap;
    }

}
