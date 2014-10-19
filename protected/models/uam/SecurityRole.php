<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\AllowableAction;

/**
 * @property Integer $id
 * @property String $name
 * @property String $description
 * @property AllowableAction[] $allowableActions;
 * @author britech
 *
 */
class SecurityRole extends Model {

    private $id;
    private $name;
    private $description;
    private $allowableActions = array();
    
    public function validate() {
        
    }
    
    public function bindValuesUsingArray(array $valueArray) {
        if(!empty($valueArray['actions'])){
            foreach($valueArray['actions'] as $actionData){
                $action = new AllowableAction();
                $action->bindValuesUsingArray($actionData);
                array_push($this->allowableActions, $action);
            }
        }    
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
