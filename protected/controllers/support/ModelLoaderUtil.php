<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Theme;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\Target;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl;
use org\csflu\isms\service\commons\UnitOfMeasureSimpleImpl;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl;
use org\csflu\isms\service\ubt\CommitmentManagementServiceSimpleImpl;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl;
use org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl;

/**
 * Description of ModelLoaderUtil
 *
 * @author britech
 */
class ModelLoaderUtil {

    const KEY_URL = "url";
    const KEY_REMOTE = "remote";
    const KEY_MSG = "message";

    private static $instance = null;
    private $controller;
    private $logger;
    private $ubtService;
    private $mapService;
    private $uomService;
    private $userService;
    private $departmentService;
    private $scorecardService;
    private $commitService;
    private $initiativeService;
    private $indicatorService;

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl;
        $this->mapService = new StrategyMapManagementServiceSimpleImpl();
        $this->uomService = new UnitOfMeasureSimpleImpl();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->departmentService = new DepartmentServiceSimpleImpl();
        $this->scorecardService = new ScorecardManagementServiceSimpleImpl();
        $this->commitService = new CommitmentManagementServiceSimpleImpl();
        $this->initiativeService = new InitiativeManagementServiceSimpleImpl();
        $this->indicatorService = new IndicatorManagementServiceSimpleImpl();
    }

    /**
     * Returns the singleton instance of the ModelLoaderUtil class
     * @param Controller $controller To utilize the redirection and session manipulation mechanisms 
     * @return ModelLoaderUtil
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new ModelLoaderUtil($controller);
        }
        return self::$instance;
    }

    /**
     * Retrieves the UnitBreakthrough entity
     * @param String $id Retrieve through its identifier
     * @param LeadMeasure $leadMeasure Retrieve through the LeadMeasure entity
     * @param WigSession $wigSession Retrieve through the WigSession entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return UnitBreakthrough
     */
    public function loadUnitBreakthroughModel($id = null, LeadMeasure $leadMeasure = null, WigSession $wigSession = null, array $properties = array()) {
        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($id, $leadMeasure, $wigSession);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Unit Breakthrough not found");

        return $this->resolveModel($updatedProperties, $unitBreakthrough);
    }

    /**
     * Retrieves the StrategyMap entity
     * @param String $id Retrieve through its identifier
     * @param Perspective $perspective Retrieve through the Perspective entity
     * @param Objective $objective Retrieve through the Objective entity
     * @param Theme $theme Retrieve through the Theme entity
     * @param Initiative $initiative Retrieve through the Initiative entity
     * @param UnitBreakthrough $unitBreakthrough Retrieve through the UnitBreakthrough entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return StrategyMap
     */
    public function loadMapModel($id = null, Perspective $perspective = null, Objective $objective = null, Theme $theme = null, Initiative $initiative = null, UnitBreakthrough $unitBreakthrough = null, array $properties = array()) {
        $map = $this->mapService->getStrategyMap($id, $perspective, $objective, $theme, $initiative, $unitBreakthrough);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Strategy Map not found");

        return $this->resolveModel($updatedProperties, $map);
    }

    /**
     * Retrieves the UnitOfMeasure entity
     * @param String $id Retrieve through its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return UnitOfMeasure
     */
    public function loadUomModel($id, array $properties = array()) {
        $uom = $this->uomService->getUomInfo($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('uom/index'), false, "No data found");

        return $this->resolveModel($updatedProperties, $uom);
    }

    /**
     * Retrieves the Department entity
     * @param String $id Retrieve through its identifier
     * @param String $code Retrieve through the Departmental code
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Department
     */
    public function loadDepartmentModel($id = null, $code = null, array $properties = array()) {
        if (!is_null($id)) {
            $department = $this->departmentService->getDepartmentDetail(array('id' => $id));
        } elseif (!is_null($code)) {
            $department = $this->departmentService->getDepartmentDetail(array('code' => $code));
        }
        $updatedProperties = $this->resolvePropertyValues($properties, array('department/index'), false, "No data found");

        return $this->resolveModel($updatedProperties, $department);
    }

    /**
     * Retrieves the Account entity
     * @param String $id Optional. If null, the function will load the Account entity through a session value
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return UserAccount
     */
    public function loadAccountModel($id = null, array $properties = array()) {
        if (is_null($id)) {
            $userAccount = $this->retrieveMyAccountModel($properties);
        } else {
            $userAccount = $this->retrieveOtherAccountModel($id, $properties);
        }
        return $userAccount;
    }

    /**
     * Retrieves the Objective entity
     * @param String $id Retrieve by its identifier
     * @param array $properties 
     * @return Objective
     */
    public function loadObjectiveModel($id, array $properties = array()) {
        $objective = $this->mapService->getObjective($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "No Objective found");
        
        return $this->resolveModel($updatedProperties, $objective);
    }

    /**
     * Retrieves the MeasureProfile entity
     * @param String $id Retrieve through its identifier
     * @param LeadOffice $leadOffice Retrieve through a LeadOffice entity
     * @param Target $target Retrieve through a Target entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @see LeadOffice
     * @see Target
     * @return MeasureProfile
     */
    public function loadMeasureProfileModel($id = null, LeadOffice $leadOffice = null, Target $target = null, array $properties = array()) {
        $measure = $this->scorecardService->getMeasureProfile($id, $leadOffice, $target);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "No Measure Profile found");
       
        return $this->resolveModel($updatedProperties, $measure);
    }

    /**
     * Retrieves the LeadMeasure entity
     * @param String $id Retrieve by its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return LeadMeasure
     */
    public function loadLeadMeasureModel($id, array $properties = array()) {
        $leadMeasure = $this->ubtService->retrieveLeadMeasure($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "No Lead Measure found");
        
        return $this->resolveModel($updatedProperties, $leadMeasure);
    }

    /**
     * Retrieves the WigSession entity
     * @param String $id Retrieve by its identifier
     * @param Commitment $commitment Retrieve through a Commitment entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return WigSession
     */
    public function loadWigSessionModel($id = null, Commitment $commitment = null, array $properties = array()) {
        $wigSession = $this->ubtService->getWigSessionData($id, $commitment);
        $updatedProperties = $this->resolvePropertyValues($properties, array('ubt/manage'), false, "WIG Session not found");

        return $this->resolveModel($updatedProperties, $wigSession);
    }

    /**
     * Retrieves the Commitment entity
     * @param String $id Retrieve by its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Commitment
     */
    public function loadCommitmentModel($id, array $properties = array()) {
        $commitment = $this->commitService->getCommitmentData($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('ubt/manage'), false, "Commitment not found");

        return $this->resolveModel($updatedProperties, $commitment);
    }

    /**
     * Retrieves the Initiative entity
     * @param String $id Retrieve by its identifier
     * @param Phase $phase Retrieve through a Phase entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Initiative
     */
    public function loadInitiativeModel($id = null, Phase $phase = null, array $properties = array()) {
        $initiative = $this->initiativeService->getInitiative($id, $phase);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Initiative not found");

        return $this->resolveModel($updatedProperties, $initiative);
    }

    /**
     * Retrieves the Phase entity
     * @param String $id Retrieve through its identifier
     * @param Component $component Retrieve through the Component entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Phase
     */
    public function loadPhaseModel($id = null, Component $component = null, array $properties = array()) {
        $phase = $this->initiativeService->getPhase($id, $component);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Initiative Phase not found");

        return $this->resolveModel($updatedProperties, $phase);
    }

    /**
     * Retrieves the Component entity
     * @param String $id Retrieve through its identifier
     * @param Activity $activity Retrieve through the Activity entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Component
     */
    public function loadComponentModel($id = null, Activity $activity = null, array $properties = array()) {
        $component = $this->initiativeService->getComponent($id, $activity);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Initiative Component not found");

        return $this->resolveModel($updatedProperties, $component);
    }

    /**
     * Retrieves the Activity entity
     * @param String $id Retrieve through its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Activity
     */
    public function loadActivityModel($id, array $properties = array()) {
        $activity = $this->initiativeService->getActivity($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('map/index'), false, "Initiative Activity not found");

        return $this->resolveModel($updatedProperties, $activity);
    }
    
    /**
     * Retrieves the Indicator entity
     * @param String $id Retrieve through its identifier
     * @param Baseline $baseline Retrieve through a Baseline entity
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Indicator
     */
    public function loadIndicatorModel($id = null, Baseline $baseline = null, array $properties = array()){
        $indicator = $this->indicatorService->retrieveIndicator($id, $baseline);
        $updatedProperties = $this->resolvePropertyValues($properties, array('km/indicators'), false, "Indicator not found");
        
        return $this->resolveModel($updatedProperties, $indicator);
    }
    
    /**
     * Retrieves the Baseline entity
     * @param String $id Retrieve through its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return Baseline
     */
    public function loadBaselineModel($id, array $properties = array()){
        $baseline = $this->indicatorService->getBaseline($id);
        $updatedProperties = $this->resolvePropertyValues($properties, array('km/indicators'), false, "Baseline data not found");
        
        return $this->resolveModel($updatedProperties, $baseline);
    }

    private function resolvePropertyValues(array $properties, $defaultUrl, $defaultRemoteIndicator, $defaultMessage) {
        $url = $this->resolveProperty($properties, self::KEY_URL, $defaultUrl);
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, $defaultRemoteIndicator);
        $message = $this->resolveProperty($properties, self::KEY_MSG, $defaultMessage);

        return array(
            self::KEY_URL => $url,
            self::KEY_REMOTE => $remote,
            self::KEY_MSG => $message
        );
    }

    private function resolveModel(array $properties, Model $model) {
        $url = ApplicationUtils::getProperty($properties, self::KEY_URL);
        $remote = ApplicationUtils::getProperty($properties, self::KEY_REMOTE);
        $message = ApplicationUtils::getProperty($properties, self::KEY_MSG);
        if (is_null($model->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $model;
    }

    private function retrieveMyAccountModel(array $properties) {
        $id = $this->controller->getSessionData('user');
        $userAccount = $this->userService->getAccountById($id);
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "Please enter your credentials to continue");
        if (is_null($userAccount->id)) {
            $url = array('site/logout');
            $this->controller->setSessionData('login.notif', $message);
            $this->logger->warn("Your account cannot be found. Forcing logout mechanism");
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $userAccount;
    }

    private function retrieveOtherAccountModel($id, array $properties) {
        $userAccount = $this->userService->getAccountById($id);
        $updatedProperties = $this->resolvePropertyValues($properties,  array('user/index'), false, "User not found");
        
        return $this->resolveModel($updatedProperties, $userAccount);
    }

    private function resolveProperty(array $properties, $propertyName, $defaultValue) {
        $property = ApplicationUtils::getProperty($properties, $propertyName);

        if (is_null($property)) {
            $property = $defaultValue;
        }
        return $property;
    }

}
