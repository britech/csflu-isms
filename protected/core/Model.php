<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ModelException;

abstract class Model {

    const VALIDATION_MODE_INITIAL = 1;
    const VALIDATION_MODE_UPDATE = 2;
    
    public $validationMode = self::VALIDATION_MODE_INITIAL;
    public $validationMessages = array();
    public $arrayDelimiter = "/";
    public $updatedFields = array();
    
    /**
     * 
     * @return boolean
     */
    abstract function validate();

    public function bindValuesUsingArray(array $valueArray, Model $model){
        $classFullName = explode('\\', get_class($model));
        $className = strtolower($classFullName[count($classFullName)-1]);
        
        if(!array_key_exists($className, $valueArray)){
            throw new ModelException("Data not found ({$classFullName[count($classFullName)-1]})");
        }
        
        foreach($valueArray[$className] as $property=>$value){
            if(!property_exists($model, $property)){
                throw new ModelException('Data binding failure');
            }
            $value = is_array($value) ? implode($this->arrayDelimiter, $value) : $value;
            $model->$property = (htmlentities(trim($value), ENT_COMPAT, 'UTF-8'));
        }
    }

    public function getAttributeNames() {
        return array();
    }
    
    public function isNew(){}
    
    public function getModelTranslationAsNewEntity(){}
    
    public function getModelTranslationAsUpdatedEntity(Model $oldModel){}
    
    public function getModelTranslationAsDeletedEntity(){}
    
    public function computePropertyChanges(Model $oldModel){}
}
