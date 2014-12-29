<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl as IndicatorManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

/**
 * Description of MeasureController
 *
 * @author britech
 */
class MeasureController extends Controller {

    private $logger;
    private $departmentService;
    private $mapService;
    private $indicatorService;
    private $scorecardService;

    public function __construct() {
        $this->checkAuthorization();
        $this->departmentService = new DepartmentService();
        $this->mapService = new StrategyMapManagementService();
        $this->indicatorService = new IndicatorManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($map) {
        if (!isset($map) || empty($map)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }
        $strategyMap = $this->loadMapModel($map);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Measure Profiles';
        $this->render('measure-profile/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Scorecard' => array('scorecard/manage', 'map' => $strategyMap->id),
                'Measure Profiles' => 'active'
            ),
            'sidebar' => array(
                'data' => array('header' => 'Actions',
                    'links' => array('Create Measure Profile' => array('measure/create'))
                )
            ),
            'model' => new MeasureProfile(),
            'objectiveModel' => new Objective,
            'indicatorModel' => new Indicator(),
            'mapModel' => $strategyMap,
            'measureTypes' => MeasureProfile::getMeasureTypes(),
            'frequencyTypes' => MeasureProfile::getFrequencyTypes(),
            'statusTypes' => MeasureProfile::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('MeasureProfile', 'Objective', 'Indicator', 'StrategyMap'));

        $measureProfileData = $this->getFormData('MeasureProfile');
        $objectiveData = $this->getFormData('Objective');
        $indicatorData = $this->getFormData('Indicator');
        $strategyMapData = $this->getFormData('StrategyMap');

        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        $measureProfile = new MeasureProfile();
        $measureProfile->bindValuesUsingArray(array('measureprofile' => $measureProfileData), $measureProfile);
        $measureProfile->objective = $this->mapService->getObjective($objectiveData['id']);
        $measureProfile->indicator = $this->indicatorService->retrieveIndicator($indicatorData['id']);

        if (!$measureProfile->validate()) {
            $this->setSessionData('validation', $measureProfile->validationMessages);
            $this->redirect(array('measure/index', 'map' => $strategyMap->id));
        }

        try {
            $id = $this->scorecardService->insertMeasureProfile($measureProfile, $strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $id, $measureProfile);
            $this->redirect(array('measure/view', 'id' => $id));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('measure/index', 'map' => $strategyMap->id));
        }
    }

    public function validateInput() {
        try {
            $this->validatePostData(array('MeasureProfile', 'Objective', 'Indicator'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $measureProfileData = $this->getFormData('MeasureProfile');
        $objectiveData = $this->getFormData('Objective');
        $indicatorData = $this->getFormData('Indicator');

        $measureProfile = new MeasureProfile();
        $measureProfile->bindValuesUsingArray(array(
            'measureprofile' => $measureProfileData,
            'objective' => $objectiveData,
            'indicator' => $indicatorData));
        $this->remoteValidateModel($measureProfile);
    }

    public function view($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $measureProfile = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $this->layout = 'column-2';
        $this->title = ApplicationConstants::APP_NAME . ' - Profile';
        $this->render('measure-profile/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Scorecard' => array('scorecard/manage', 'map' => $strategyMap->id),
                'Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Profile' => array('measure/update', 'id' => $measureProfile->id),
                        'Manage Lead Offices' => array('measure/manageOffices', 'profile' => $measureProfile->id),
                        'Manage Targets' => array('measure/manageTargets', 'profile' => $measureProfile->id)
                    )
                )
            ),
            'model' => $measureProfile
        ));
    }

    public function manageOffices($profile) {
        if (!isset($profile) || empty($profile)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $measureProfile = $this->loadModel($profile);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Offices';
        $this->render('measure-profile/lead-office', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Scorecard' => array('scorecard/manage', 'map' => $strategyMap->id),
                'Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Manage Lead Offices' => 'active'
            ),
            'model' => new LeadOffice(),
            'departmentModel' => new Department,
            'measureProfileModel' => $measureProfile,
            'designationTypes' => LeadOffice::getDesignationOptions(),
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function validateLeadOfficeInput() {
        try {
            $this->validatePostData(array('LeadOffice', 'Department'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $leadOfficeData = $this->getFormData('LeadOffice');
        $departmentData = $this->getFormData('Department');

        $leadOffice = new LeadOffice();
        $leadOffice->bindValuesUsingArray(array(
            'leadoffice' => $leadOfficeData,
            'department' => $departmentData
        ));

        $this->remoteValidateModel($leadOffice);
    }

    public function listLeadOffices() {
        $this->validatePostData(array('profile'));

        $profile = $this->getFormData('profile');
        $measureProfile = $this->scorecardService->getMeasureProfile($profile);

        $data = array();
        foreach ($measureProfile->leadOffices as $leadOffice) {
            $designations = explode($leadOffice->arrayDelimiter, $leadOffice->designation);
            foreach ($designations as $designation) {
                array_push($data, array(
                    'id' => $leadOffice->id,
                    'department' => $leadOffice->department->name,
                    'designation' => LeadOffice::getDesignationOptions()[$designation]
                ));
            }
        }

        $this->renderAjaxJsonResponse($data);
    }

    public function insertLeadOffice() {
        $this->validatePostData(array('LeadOffice', 'Department', 'MeasureProfile'));
        $leadOfficeData = $this->getFormData('LeadOffice');
        $departmentData = $this->getFormData('Department');
        $measureProfileData = $this->getFormData('MeasureProfile');

        $leadOffices = array();
        foreach (explode("/", $departmentData['id']) as $department) {
            $leadOffice = new LeadOffice();
            $leadOffice->bindValuesUsingArray(array(
                'leadoffice' => $leadOfficeData
            ));
            $leadOffice->department = $this->departmentService->getDepartmentDetail(array('id'=>$department));
            array_push($leadOffices, $leadOffice);
        }

        $measureProfile = $this->loadModel($measureProfileData['id']);
        $measureProfile->leadOffices = $leadOffices;
        $this->logger->debug($measureProfile);
        
        if ($leadOffice->validate()) {
            $this->setSessionData('notif', array('class' => 'success', 'message' => 'Lead Office added'));
        } else {
            $this->setSessionData('validation', $leadOffice->validationMessages);
        }
        $this->redirect(array('measure/manageOffices', 'profile' => $measureProfile->id));
    }

    private function loadModel($id) {
        $measureProfile = $this->scorecardService->getMeasureProfile($id);
        if (is_null($measureProfile->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Measure Profile not found'));
            $this->redirect(array('map/index'));
        }
        return $measureProfile;
    }

    private function loadMapModel($id = null, Objective $objective = null) {
        $map = $this->mapService->getStrategyMap($id, null, $objective);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

}
