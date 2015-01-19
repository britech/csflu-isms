<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ModelException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;

/**
 * Description of InitiativeController
 *
 * @author britech
 */
class InitiativeController extends Controller {

    private $logger;
    private $mapService;
    private $scorecardService;
    private $departmentService;
    private $initiativeService;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->departmentService = new DepartmentService();
        $this->initiativeService = new InitiativeManagementService();
    }

    public function index($map) {
        $strategyMap = $this->loadMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Initiatives';
        $this->layout = 'column-2';
        $this->render('initiative/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Initiatives' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Create an Initiative' => array('initiative/create', 'map' => $strategyMap->id)
                    )
                )
            ),
            'map' => $strategyMap->id
        ));
    }

    public function listInitiatives() {
        $this->validatePostData(array('map'));

        $map = $this->getFormData('map');
        $strategyMap = $this->loadMapModel($map);

        $initiatives = $this->initiativeService->listInitiatives($strategyMap);
        $data = array();
        foreach ($initiatives as $initiative) {
            array_push($data, array(
                'initiative' => $initiative->title,
                'action' => ApplicationUtils::generateLink(array('initiative/manage', 'id' => $initiative->id), 'Manage')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create($map) {
        $strategyMap = $this->loadMapModel($map);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');

        $model = new Initiative();
        $model->startingPeriod = $strategyMap->startingPeriodDate;
        $model->endingPeriod = $strategyMap->endingPeriodDate;

        $this->title = ApplicationConstants::APP_NAME . ' - Create an Initiative';
        $this->render('initiative/profile', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Initiatives' => array('initiative/index', 'map' => $strategyMap->id),
                'Create an Initiative' => 'active'
            ),
            'model' => $model,
            'mapModel' => $strategyMap,
            'objectiveModel' => new Objective(),
            'measureModel' => new MeasureProfile(),
            'departmentModel' => new Department(),
            'statusTypes' => Initiative::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('Initiative', 'Objective', 'MeasureProfile', 'Department', 'StrategyMap'));

        $initiativeData = $this->getFormData('Initiative');
        $objectiveData = $this->getFormData('Objective');
        $measureData = $this->getFormData('MeasureProfile');
        $departmentData = $this->getFormData('Department');

        $strategyMapData = $this->getFormData('StrategyMap');
        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        $initiative = new Initiative();
        $initiative->bindValuesUsingArray(array(
            'initiative' => $initiativeData,
            'objectives' => $objectiveData,
            'leadMeasures' => $measureData,
            'implementingOffices' => $departmentData
        ));

        if (!$initiative->validate()) {
            $this->setSessionData('validation', $initiative->validationMessages);
            $this->redirect(array('initiative/create', 'map' => $strategyMap->id));
        }

        $purifiedInitiative = $this->purifyInitiativeInput($initiative);
        try {
            $id = $this->initiativeService->addInitiative($initiative, $strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $id, $purifiedInitiative);

            foreach ($initiative->objectives as $objective) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $id, "[Objective linked]\n\nObjective:\t{$objective->description}");
            }

            foreach ($initiative->leadMeasures as $leadMeasure) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $id, "[LeadMeasure linked]\n\nLead Measure:\t{$leadMeasure->indicator->description}");
            }

            foreach ($initiative->implementingOffices as $implementingOffice) {
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $id, $implementingOffice);
            }
            $this->redirect(array('initiative/manage', 'id' => $id));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('initiative/create', 'map' => $strategyMap->id));
        }
    }

    private function purifyInitiativeInput(Initiative $initiative) {
        //purify objectives
        if (count($initiative->objectives) > 0) {
            $objectives = array();
            foreach ($initiative->objectives as $data) {
                array_push($objectives, $this->mapService->getObjective($data->id));
            }
            $initiative->objectives = $objectives;
        }

        //purify leadMeasures
        if (count($initiative->leadMeasures) > 0) {
            $leadMeasures = array();
            foreach ($initiative->leadMeasures as $data) {
                array_push($leadMeasures, $this->scorecardService->getMeasureProfile($data->id));
            }
            $initiative->leadMeasures = $leadMeasures;
        }

        //purify implementingOffices
        if (count($initiative->implementingOffices) > 0) {
            $implementingOffices = array();
            foreach ($initiative->implementingOffices as $data) {
                $data->department = $this->departmentService->getDepartmentDetail(array('id' => $data->department->id));
                array_push($implementingOffices, $data);
            }
            $initiative->implementingOffices = $implementingOffices;
        }
        return $initiative;
    }

    public function validateInitiativeInput() {
        $initiative = new Initiative();
        try {
            $this->validatePostData(array('mode'));
            $mode = $this->getFormData('mode');

            if ($mode == Model::VALIDATION_MODE_INITIAL) {
                $this->validatePostData(array('Initiative', 'Objective', 'MeasureProfile', 'Department'));
                $initiativeData = $this->getFormData('Initiative');
                $objectiveData = $this->getFormData('Objective');
                $measureData = $this->getFormData('MeasureProfile');
                $departmentData = $this->getFormData('Department');
                $initiative->bindValuesUsingArray(array(
                    'initiative' => $initiativeData,
                    'objectives' => $objectiveData,
                    'leadMeasures' => $measureData,
                    'implementingOffices' => $departmentData
                ));
            } elseif ($mode == Model::VALIDATION_MODE_UPDATE) {
                $this->validatePostData(array('Initiative'));
                $initiativeData = $this->getFormData('Initiative');
                $initiative->bindValuesUsingArray(array(
                    'initiative' => $initiativeData,
                ));
            }
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        } catch (ModelException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '60'));
            $this->logger->error($ex->getMessage(), $ex);
        }
        $this->remoteValidateModel($initiative);
    }

    private function loadMapModel($id) {
        $map = $this->mapService->getStrategyMap($id);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

}
