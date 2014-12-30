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
        $counter = 0;
        if (strlen($this->department->id) == 0) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['department']} should be defined");
            $counter++;
        }

        if (empty($this->designation)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['designation']} should be defined");
            $counter++;
        } else {
            $counter = $this->validateDesignationInput($counter);
        }

        return $counter == 0;
    }

    private function validateDesignationInput($counter) {
        $input = explode($this->arrayDelimiter, $this->designation);
        $valid = 0;
        for ($i = 0; $i < count($input); $i++) {
            if (array_key_exists($input[$i], self::getDesignationOptions())) {
                $valid++;
            }
        }

        if ($valid != count($input)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['designation']} selection invalid");
            $counter++;
        }

        return $counter;
    }

    public static function getDesignationOptions() {
        return array(self::RESPONSIBILITY_SETTER => 'Setter of Targets',
            self::RESPONSIBILITY_ACCOUNTABLE => 'Accountable for the Targets',
            self::RESPONSBILITY_TRACKER => 'Tracker and Reporter of Targets');
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('department', $valueArray)) {
            $this->department = new Department();
            $this->department->bindValuesUsingArray($valueArray, $this->department);
        }
        parent::bindValuesUsingArray($valueArray, $this);
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

    public function getModelTranslationAsNewEntity() {
        $designationInputs = explode($this->arrayDelimiter, $this->designation);
        $designationValues = array();
        foreach ($designationInputs as $input) {
            array_push($designationValues, self::getDesignationOptions()[$input]);
        }
        return "[LeadOffice added]\n\n"
                . "Department:\t{$this->department->name}\n"
                . "Designation:\t" . implode($this->arrayDelimiter, $designationValues);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $leadOffice = new LeadOffice();
        $leadOffice->id = $this->id;
        $leadOffice->department = $this->department;
        $leadOffice->designation = $this->designation
    }

    public function __toString() {
        return "[LeadOffice] (id=>{$this->id}, department=>{$this->department->id}-{$this->department->name}, designation=>{$this->designation})";
    }

}
