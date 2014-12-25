<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * Description of Baseline
 *
 * @property String $id
 * @property String $baselineDataGroup
 * @property String $coveredYear
 * @property String $value
 * @property String $notes
 * @author britech
 */
class Baseline extends Model {

    private $id;
    private $baselineDataGroup;
    private $coveredYear;
    private $value;
    private $notes;

    public function validate() {
        $counter = 0;
        if (empty($this->coveredYear)) {
            $counter+=1;
            array_push($this->validationMessages, '- Covered Year must be defined');
        }

        if (empty($this->value)) {
            switch (strlen($this->value)) {
                case 0:
                    $counter+=1;
                    array_push($this->validationMessages, '- Figure Value should be defined');
                    break;
                default:
                    $this->value = floatval($this->value);
            }
        }

        return $counter > 0 ? false : true;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getAttributeNames() {
        return array(
            'baselineDataGroup' => 'Item Name',
            'coveredYear' => 'Covered Year',
            'value' => 'Figure Value',
            'notes' => 'Notes');
    }

    public function computePropertyChanges(Baseline $oldModel) {
        $counter = 0;
        if ($this->baselineDataGroup != $oldModel->baselineDataGroup) {
            $counter++;
        }

        if ($this->coveredYear != $oldModel->coveredYear) {
            $counter++;
        }

        if ($this->value != $oldModel->value) {
            $counter++;
        }

        return $counter;
    }

    public function __clone() {
        $baseline = new Baseline();
        $baseline->id = $this->id;
        $baseline->baselineDataGroup = $this->baselineDataGroup;
        $baseline->coveredYear = $this->coveredYear;
        $baseline->value = $this->value;
    }

    public function __toString() {
        return "Baseline[id=>{$this->id}, baselineDataGroup=>{$this->baselineDataGroup}, coveredYear=>{$this->coveredYear}, value=>{$this->value}]";
    }

}
