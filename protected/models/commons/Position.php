<?php

namespace org\csflu\isms\models\commons;

use org\csflu\isms\core\Model;
/**
 * @property Integer $id
 * @property String $name
 * @author britech
 *
 */
class Position extends Model {

    private $id;
    private $name;
    
    public function validate() {
        
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
