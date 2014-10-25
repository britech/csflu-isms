<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\ModuleAction;

/**
 * @property Integer $id
 * @property ModuleAction $module
 * @author britech
 *
 */
class AllowableAction extends Model {

    private $id;
    private $module;
    
    public function validate() {
        
    }
    
    public function bindValuesUsingArray(array $valueArray) {
        if(array_key_exists('allowableactions', $valueArray)){
            parent::bindValuesUsingArray($valueArray, $this);
        }
        
        if(array_key_exists('module', $valueArray)){
            $this->module = new ModuleAction();
            $this->module->module = $valueArray['module']['module'];
            $this->module->actions = implode('/', $valueArray['module']['actions']);
        }
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
