<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ModelException;

/**
 * @property Integer $validationMode
 * @property array $validationMessages
 */
abstract class Model {

    const VALIDATION_MODE_INITIAL = 1;
    const VALIDATION_MODE_UPDATE = 2;
    
    private $validationMode;
    protected $validationMessages = array();
    /**
     * 
     * @return boolean
     */
    abstract function validate();

    public function bindValuesUsingArray(array $valueArray, $model=null){
        $classFullName = explode('\\', get_class($model));
        $className = strtolower($classFullName[count($classFullName)-1]);
        
        if(!array_key_exists($className, $valueArray)){
            throw new ModelException("Data not found ({$classFullName[count($classFullName)-1]})");
        }
        
        foreach($valueArray[$className] as $property=>$value){
            
            if(!property_exists($model, $property)){
                throw new ModelException('Data binding failure');
            }
            $model->$property = $value;
        }
    }

    public function getAttributeNames() {
        return array();
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
