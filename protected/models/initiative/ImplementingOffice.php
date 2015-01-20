<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\Department;

/**
 * Description of ImplementingOffice
 *
 * @property String $id
 * @property Department $department
 * @property String $designation
 * @author britech
 */
class ImplementingOffice extends Model {

    const DESIGNATION_OWNER = "H";
    const DESIGNATION_DEPUTY = "A";
    const DESIGNATION_MEMBER = "M";

    private $id;
    private $department;
    private $designation = self::DESIGNATION_OWNER;

    public static function getDesignationTypes() {
        return array(
            self::DESIGNATION_OWNER => 'Owner',
            self::DESIGNATION_DEPUTY => 'Assistant',
            self::DESIGNATION_MEMBER => 'Member'
        );
    }

    public function validate() {
        
    }

    public function getAttributeNames() {
        return array(
            'department' => 'Implementing Office',
            'designation' => 'Designation'
        );
    }
    
    public function getModelTranslationAsNewEntity() {
        return "[ImplementingOffice added]\n\n"
        . "Department:\t{$this->department->name}\n"
        . "Designation:\t{$this->getDesignationTypes()[$this->designation]}";
    }
    
    public function getModelTranslationAsDeletedEntity() {
        return "[ImplementingOffice deleted]\n\n"
        . "Department:\t{$this->department->name}\n"
        . "Designation:\t{$this->getDesignationTypes()[$this->designation]}";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }
    
    public function __clone() {
        $implementingOffice = new ImplementingOffice();
        $implementingOffice->id = $this->id;
        $implementingOffice->department = $this->department;
        $implementingOffice->designation = $this->designation;
    }

}
