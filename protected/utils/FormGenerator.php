<?php

namespace org\csflu\isms\util;

use org\csflu\isms\util\Component as Component;
use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;

/**
 *
 * @author Brian Angyang
 * */
class FormGenerator extends Component {

    const METHOD_POST = "POST";
    const METHOD_GET = "GET";
    const METHOD_DELETE = "DELETE";
    const METHOD_PUT = "PUT";
    
    const PROPERTY_METHOD = "method";
    const PROPERTY_ACTION = "action";
    const PROPERTY_ENCTYPE = "enctype";
    const PROPERTY_FIELDSETENABLED = "hasFieldset";

    private $properties;
    private $fieldsetEnabled = false;

    public function __construct(array $properties) {
        $this->properties = $properties;

        $fieldsetEnabledStatus = ApplicationUtils::getProperty($properties, self::PROPERTY_FIELDSETENABLED);
        $this->fieldsetEnabled = is_null($fieldsetEnabledStatus) ? false : $fieldsetEnabledStatus;
    }

    public function startComponent() {
        $class = ApplicationUtils::getProperty($this->properties, parent::PROPERTY_CLASS);
        $id = ApplicationUtils::getProperty($this->properties, parent::PROPERTY_ID);
        $style = ApplicationUtils::getProperty($this->properties, parent::PROPERTY_STYLE);
        $action = ApplicationUtils::resolveUrl($this->filterActionProperty());
        $method = $this->filterMethodProperty();

        $openingFormTag = "<form action=\"{$action}\" method=\"{$method}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\">";
        $openingComponentTag = $this->fieldsetEnabled ? $openingFormTag . "<fieldset>" : $openingFormTag;

        return $openingComponentTag;
    }

    private function filterActionProperty() {
        $action = ApplicationUtils::getProperty($this->properties, self::PROPERTY_ACTION);
        return is_null($action) ? "#" : $action;
    }

    private function filterMethodProperty() {
        $method = ApplicationUtils::getProperty($this->properties, self::PROPERTY_METHOD);
        return is_null($method) ? self::METHOD_POST : $method;
    }

    public static function renderLabel($labelText, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<label class=\"{$class}\" id=\"{$id}\" style=\"{$style}\">${labelText}</label>";
        } else {
            return "<label>${labelText}</label>";
        }
    }

    public static function renderTextField($fieldName, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<input type=\"text\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\"/>";
        } else {
            return "<input type=\"text\" name=\"{$fieldName}\"/>";
        }
    }

    public static function renderPasswordField($fieldName, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<input type=\"password\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\"/>";
        } else {
            return "<input type=\"password\" name=\"{$fieldName}\"/>";
        }
    }
    
    public static function renderDropDownList($fieldName, array $data, array $properties = null) {
        $tag = "";
        if(!is_null($properties)){
            
        } else{
            $tag = "<select name=\"{$fieldName}\">";
        }
        foreach($data as $value => $text){
            if(is_array($text)){
                
            } else{
                $tag.="<option value=\"${value}\">{$text}</option>";
            }
        }
        
        $tag.="</select>";
        return $tag;
    }

    public static function renderSubmitButton($buttonName, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<button type=\"submit\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\">{$buttonName}</button>";
        } else {
            return "<button type=\"submit\">{$buttonName}</button>";
        }
    }

    public function endComponent() {
        $closingFormTag = "</form>";
        $closingComponentTag = $this->fieldsetEnabled ? "</fieldset>" . $closingFormTag : $closingFormTag;
        return $closingComponentTag;
    }

    public function renderComponent() {
        throw new \BadMethodCallException("The class does not support this method!");
    }

    public function constructHeader($headerText, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<legend id=\"{$id}\" class=\"{$class}\" style=\"{$style}\">{$headerText}</legend>";
        } else {
            return "<legend>{$headerText}</legend>";
        }
    }

}