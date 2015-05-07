<?php

namespace org\csflu\isms\models\commons;

use org\csflu\isms\core\Model;

/**
 * Description of UnitOfMeasure
 *
 * @property Integer $id
 * @property String $symbol
 * @property String $description
 * @author britech
 */
class UnitOfMeasure extends Model {

    private $id;
    private $symbol;
    private $description;

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function validate() {
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Description should not be empty!');
            return false;
        } else {
            return true;
        }
    }

    public function getAttributeNames() {
        return array('symbol' => 'Symbol', 'description' => 'Unit of Measure');
    }

    public function getAppropriateUomDisplay() {
        return strlen($this->symbol) < 1 ? $this->description : $this->symbol;
    }

}
