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
class UnitOfMeasure extends Model{

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
        if(empty($this->description)){
            array_push($this->validationMessages, '- Description should not be empty!');
            return false;
        } else {
            return true;
        }
    }

}
