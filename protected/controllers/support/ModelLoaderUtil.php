<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
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
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl;
use org\csflu\isms\service\commons\UnitOfMeasureSimpleImpl;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl;

/**
 * Description of ModelLoaderUtil
 *
 * @author britech
 */
class ModelLoaderUtil {

    const KEY_URL = "url";
    const KEY_REMOTE = "isRemote";
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

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl;
        $this->mapService = new StrategyMapManagementServiceSimpleImpl();
        $this->uomService = new UnitOfMeasureSimpleImpl();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->departmentService = new DepartmentServiceSimpleImpl();
        $this->scorecardService = new ScorecardManagementServiceSimpleImpl();
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
        $url = $this->resolveProperty($properties, self::KEY_URL, array('map/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "Unit Breakthrough not found");
        if (is_null($unitBreakthrough->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $unitBreakthrough;
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
        $url = $this->resolveProperty($properties, self::KEY_URL, array('map/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "Strategy Map not found");
        if (is_null($map->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $map;
    }

    /**
     * Retrieves the UnitOfMeasure entity
     * @param String $id Retrieve through its identifier
     * @param array $properties Properties to set the redirection and session manipulation mechanisms of the underlying controller
     * @return UnitOfMeasure
     */
    public function loadUomModel($id, array $properties = array()) {
        $uom = $this->uomService->getUomInfo($id);
        $url = $this->resolveProperty($properties, self::KEY_URL, array('uom/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "No data found");
        if (is_null($uom->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $uom;
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

        $url = $this->resolveProperty($properties, self::KEY_URL, array('department/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "No data found");
        if (is_null($department->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $department;
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
        $url = $this->resolveProperty($properties, self::KEY_URL, array('map/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "No Objective found");
        if (is_null($objective->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $objective;
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
        $url = $this->resolveProperty($properties, self::KEY_URL, array('map/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "No Measure Profile found");
        if (is_null($measure->id)) {
            $this->controller->setSessionData('notif', array('message' => $message));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $measure;
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
        $url = $this->resolveProperty($properties, self::KEY_URL, array('user/index'));
        $remote = $this->resolveProperty($properties, self::KEY_REMOTE, false);
        $message = $this->resolveProperty($properties, self::KEY_MSG, "User not found");
        if (is_null($userAccount->id)) {
            $this->controller->setSessionData('notif', $message);

            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $userAccount;
    }

    private function resolveProperty(array $properties, $propertyName, $defaultValue) {
        $property = ApplicationUtils::getProperty($properties, $propertyName);

        if (is_null($property)) {
            $this->logger->warn("null value for {$propertyName}. Using default {$defaultValue}");
            $property = $defaultValue;
        }
        return $property;
    }

}
