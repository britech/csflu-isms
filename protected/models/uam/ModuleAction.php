<?php

namespace org\csflu\isms\models\uam;

/**
 * Description of ModuleAction
 *
 * @property String $module
 * @property String[] $actions
 * @author britech
 */
class ModuleAction {

    const MODULE_SMAP = "SMAP";
    const MODULE_SCARD = "SCARD";
    const MODULE_INITIATIVE = "INI";
    const MODULE_UBT = "UBT";
    const MODULE_IP = "IP";
    const MODULE_KM = "KM";
    const MODULE_SYS = "SYS";

    private $module;
    private $actions;

    public function getAllowableActionByModule($module) {
        switch ($module) {
            case self::MODULE_SMAP:
                return $this->getAllowableActionsForStrategyMapModule();
            case self::MODULE_SCARD:
                return $this->getAllowableActionsForScorecardModule();
            case self::MODULE_INITIATIVE:
                return $this->getAllowableActionsForInitiativeModule();
            case self::MODULE_UBT:
                return $this->getAllowableActionsForUbtModule();
            case self::MODULE_IP:
                return $this->getAllowableActionsForIpModule();
            case self::MODULE_KM:
                return $this->getAllowableActionsForKmModule();
            case self::MODULE_SYS:
                return $this->getAllowableActionsForAdminModule();
        }
    }

    public function getModules(){
        return array(
            self::MODULE_SMAP => 'Strategy Map',
            self::MODULE_SCARD => 'Scorecard',
            self::MODULE_INITIATIVE => 'Strategic Initiatives',
            self::MODULE_UBT => 'Unit Breakthrough',
            self::MODULE_IP => 'Individual Performance',
            self::MODULE_KM => 'Knowledge Management',
            self::MODULE_SYS => 'System Administration'
        );
    }
    
    public function getModuleName($module){
        return $this->getModules()[$module];
    }

    private function getAllowableActionsForStrategyMapModule() {
        return array('M' => 'Manage Strategy Map', 'V' => 'View Strategy Map');
    }

    private function getAllowableActionsForScorecardModule() {
        return array('M' => 'Manage Scorecard Infra and Profile', 'U' => 'Update Movement of Scorecard Indicator', 'V' => 'View Scorecard Infra and Profile');
    }

    private function getAllowableActionsForInitiativeModule() {
        return array('MP' => 'Manage Initiative Profile', 'MU' => 'Manage Initiative Update', 'VP' => 'View Initiative Profile', 'VU' => 'View Initiative Update', 'GU' => 'Generate Initiative Update Template');
    }

    private function getAllowableActionsForUbtModule() {
        return array('M' => 'Maintain UBT Declaration', 'U' => 'Update UBT Movement', 'VB' => 'View UBT Movement', 'G' => 'Generate WIG Meeting Template');
    }

    private function getAllowableActionsForIpModule() {
        return array('M' => 'Manage Commitments', 'V' => 'View Commitments', 'VC' => 'Validate Commitment Status');
    }

    private function getAllowableActionsForKmModule() {
        return array('MI' => 'Manage Indicators', 'GR' => 'Generate Reports');
    }

    private function getAllowableActionsForAdminModule() {
        return array('MU' => 'Manage Users', 'MS' => 'Manage Security Roles', 'MD' => 'Manage Departments', 'MM' => 'Manage UOM');
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
