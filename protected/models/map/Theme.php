<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;

/**
 * 
 * @property String $id
 * @property String $description
 *
 * @author britech
 */
class Theme extends Model {

    private $id;
    private $description;
    
    public function validate() {
        
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }
}
