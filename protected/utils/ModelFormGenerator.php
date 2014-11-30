<?php

namespace org\csflu\isms\util;

use org\csflu\isms\util\FormGenerator;
use org\csflu\isms\core\Model;
use org\csflu\isms\exceptions\ModelException;

/**
 * Description of ModelFormGenerator
 *
 * @author britech
 */
class ModelFormGenerator extends FormGenerator {

    const KEY_REQUIRED = "required";

    public function __construct(array $properties) {
        parent::__construct($properties);
    }

    public function renderLabel(Model $model, $fieldName, array $properties = []) {
        if(array_key_exists('required', $properties)){
            $required = $properties['required'];
            $labelText = $this->generateLabel($model, $fieldName, $required);
        } else {
            $labelText = $this->generateLabel($model, $fieldName);
        }
        
        return parent::renderLabel($labelText, $properties);
    }

    public function renderTextField(Model $model, $fieldName, array $properties = []) {
        $properties = $this->validateAndRetrieve($model, $fieldName, $properties);
        return parent::renderTextField($this->generateFieldName($model, $fieldName), $properties);
    }

    public function renderDropDownList(Model $model, $fieldName, array $data, array $properties = []) {
        $properties = $this->validateAndRetrieve($model, $fieldName, $properties);
        return parent::renderDropDownList($this->generateFieldName($model, $fieldName), $data, $properties);
    }

    public function renderHiddenField(Model $model, $fieldName, array $properties = []) {
        $properties = $this->validateAndRetrieve($model, $fieldName, $properties);
        return parent::renderHiddenField($this->generateFieldName($model, $fieldName), $properties);
    }

    private function validateAndRetrieve(Model $model, $fieldName, array $properties) {
        if (!property_exists($model, $fieldName)) {
            throw new ModelException("Property defined not found {$fieldName}");
        }
        return $this->getModelValue($model, $fieldName, $properties);
    }

    private function getModelValue(Model $model, $fieldName, array $properties) {
        if (!is_null($model->$fieldName) || !empty($model->$fieldName)) {
            return array_merge($properties, array('value' => $model->$fieldName));
        }
        return $properties;
    }

    private function generateLabel(Model $model, $fieldName, $required = false) {
        if (!property_exists($model, $fieldName)) {
            throw new ModelException("Property defined not found {$fieldName}");
        }

        if ($required) {
            return $model->getAttributeNames()[$fieldName] . '&nbsp;*';
        } else {
            return $model->getAttributeNames()[$fieldName];
        }
    }

    private function generateFieldName(Model $model, $fieldName) {
        $classFullName = explode('\\', get_class($model));
        $className = $classFullName[count($classFullName) - 1];
        return $className . "[{$fieldName}]";
    }

}
