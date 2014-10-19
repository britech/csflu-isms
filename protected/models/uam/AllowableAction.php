<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\exceptions\ModelException;

/**
 * @property Integer $id
 * @property String $module
 * @property String[] $actions;
 * @author britech
 *
 */
class AllowableAction extends Model {

    const MODULE_SMAP = "SMAP";
    const MODULE_SCARD = "SCARD";
    const MODULE_INITIATIVE = "INI";
    const MODULE_UBT = "UBT";
    const MODULE_IP = "IP";
    const MODULE_KM = "KM";
    const MODULE_SYS = "SYS";
    
    private $id;
    private $module;
    private $actions;

    public function validate() {
        
    }

    public function bindValuesUsingArray(array $valueArray) {
        foreach($valueArray as $property=>$value){
            if(!property_exists($this, $property)){
                throw new ModelException('Data binding failure');
            }
            $this->$property = $value;
        }
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
