<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;

/**
 * Description of Perspective
 *
 * @author britech
 */
class Perspective extends Model {

    private $id;
    private $description;
    private $positionOrder;
    
    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
