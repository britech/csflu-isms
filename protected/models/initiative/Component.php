<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\initiative\Activity;

/**
 * Description of Component
 *
 * @property String $id
 * @property String $description
 * @property Activity[] $activities
 * @author britech
 */
class Component extends Model {

    private $id;
    private $description;
    private $activities;

    public function __construct($description = null, $id = null) {
        if (!is_null($description)) {
            $this->description = $description;
        }

        if (!is_null($id)) {
            $this->id = $id;
        }
    }

    public function validate() {
        
    }

    public function isNew() {
        return empty($this->id);
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
