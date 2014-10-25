<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\AllowableAction;

/**
 * @property Integer $id
 * @property String $description
 * @property AllowableAction[] $allowableActions
 * @author britech
 *
 */
class SecurityRole extends Model {

    private $id;
    private $description;
    private $allowableActions;

    public function validate() {
        
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('allowableactions', $valueArray)) {
            
        }
        if (array_key_exists('moduleactions', $valueArray)) {
            $this->allowableActions = array();
            foreach ($valueArray['moduleactions'] as $module) {
                foreach ($module as $moduleCode => $actions) {
                    $allowableAction = new AllowableAction();
                    $allowableAction->bindValuesUsingArray(array('module' => array('module' => $moduleCode, 'actions' => $actions)));
                    array_push($this->allowableActions, $allowableAction);
                }
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
