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

    public static function getModules() {
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

    public static function getModulesWithoutDescription() {
        return array(
            self::MODULE_SMAP,
            self::MODULE_SCARD,
            self::MODULE_INITIATIVE,
            self::MODULE_UBT,
            self::MODULE_IP,
            self::MODULE_KM,
            self::MODULE_SYS
        );
    }

    public function getModuleName($module) {
        return $this->getModules()[$module];
    }

    private function getAllowableActionsForStrategyMapModule() {
        return array('SMAPM' => 'Manage Strategy Map', 'SMAPV' => 'View Strategy Map');
    }

    private function getAllowableActionsForScorecardModule() {
        return array('MPM' => 'Manage Measure Profile', 'MPMOV' => 'Measure Profile Movement', 'MPV' => 'View Measure Profile');
    }

    private function getAllowableActionsForInitiativeModule() {
        return array('INIM' => 'Manage Initiative Profile', 'INIUPD' => 'Initiative Update', 'INIV' => 'View Initiative Profile');
    }

    private function getAllowableActionsForUbtModule() {
        return array('UBTM' => 'Manage UBT', 'UBTMOV' => 'UBT Movements', 'WIGM' => 'Manage WIG Sessions');
    }

    private function getAllowableActionsForIpModule() {
        return array('IPM' => 'Commitments', 'IPMRPT' => 'Individual Scorecard');
    }

    private function getAllowableActionsForKmModule() {
        return array('INDM' => 'Indicators',
            'RPTSCARDTMP' => 'Generate Scorecard Template',
            'RPTSCARDUPD' => 'Generate Scorecard Update',
            'RPTMP' => 'Generate Measure Profile',
            'RPTINIPOW' => "Generate Initiative's Program of Work",
            'RPTINIUPD' => "Generate Initiative's Update Template",
            'RPTWIGMT' => 'Generated WIG Meeting Report');
    }

    private function getAllowableActionsForAdminModule() {
        return array('MU' => 'Manage Users', 'MS' => 'Manage Security Roles', 'MD' => 'Manage Departments', 'MM' => 'Manage UOM', 'MP' => 'Manage Positions');
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
