<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\Department;

/**
 * @property String $id
 * @property Department $department
 * @property String $designation 
 * @author britech
 */
class LeadOffice extends Model {

    const RESPONSIBILITY_SETTER = "S";
    const RESPONSIBILITY_ACCOUNTABLE = "A";
    const RESPONSBILITY_TRACKER = "T";

    private $id;
    private $department;
    private $designation;

    public function validate() {
        
    }

    public static function getDesignationOptions() {
        return array(self::RESPONSIBILITY_SETTER => 'Setter of Targets',
            self::RESPONSIBILITY_ACCOUNTABLE => 'Accountable for the Targets',
            self::RESPONSBILITY_TRACKER => 'Tracker and Reporter of Targets');
    }

    public function getAttributeNames() {
        return array(
            'department' => 'Department',
            'designation' => 'Designation'
        );
    }

    public function isNew() {
        return empty($this->id);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
