<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\initiative\Component;

/**
 * Description of Phase
 *
 * @property String $id
 * @property String $title
 * @property String $description
 * @property int $positionOrder
 * @property Component[] $components
 * @author britech
 */
class Phase extends Model {

    private $id;
    private $title;
    private $description;
    private $positionOrder;
    private $components;
    
    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
