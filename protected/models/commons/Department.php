<?php

namespace org\csflu\isms\models\commons;

use org\csflu\isms\core\Model;

/**
 * @property Integer $id
 * @property String $code
 * @property String $name

 * @author britech
 *
 */
class Department extends Model {

    private $id;
    private $code;
    private $name;
    
    public function validate() {
        $validationCount = 0;
        if ($this->validationMode == Model::VALIDATION_MODE_INITIAL) {
            if(empty($this->code)){
                $validationCount+=1;
                array_push($this->validationMessages, '- Code should not be empty');
            }
            
            if(empty($this->name)){
                $validationCount+=1;
                array_push($this->validationMessages, '- Name should not be empty!');
            }
        }
        
        return $validationCount > 0 ? false : true;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
