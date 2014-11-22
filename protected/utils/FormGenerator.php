<?php

namespace org\csflu\isms\util;

use org\csflu\isms\util\Component;
use org\csflu\isms\util\ApplicationUtils;

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
    const PROPERTY_READONLY = "readonly";
    const PROPERTY_DISABLED = "disabled";
    const PROPERTY_VALUE = "value";
    const PROPERTY_CHECKED = "checked";
    const PROPERTY_ROWS = "rows";

    private $properties;
    private $fieldsetEnabled = false;
    public $tabIndex = 0;

    public function __construct(array $properties) {
        $this->properties = $properties;
        $this->tabIndex = 0;
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

    public function renderLabel($labelText, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<label class=\"{$class}\" id=\"{$id}\" style=\"{$style}\">${labelText}</label>";
        } else {
            return "<label>${labelText}</label>";
        }
    }

    public function renderTextField($fieldName, array $properties = null) {
        $this->tabIndex+=1;
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $readOnly = ApplicationUtils::getProperty($properties, self::PROPERTY_READONLY);
            $disabled = ApplicationUtils::getProperty($properties, self::PROPERTY_DISABLED);
            $value = ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE);

            if (!empty($readOnly) && $readOnly == true) {
                return "<input type=\"text\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" readonly tabindex=\"{$this->tabIndex}\" value=\"{$value}\"/>";
            } elseif (!empty($disabled) && $disabled == true) {
                return "<input type=\"text\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" disabled tabindex=\"{$this->tabIndex}\" value=\"{$value}\"/>";
            } else {
                return "<input type=\"text\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\" value=\"{$value}\"/>";
            }
        } else {
            return "<input type=\"text\" name=\"{$fieldName}\" tabindex=\"{$this->tabIndex}\"/>";
        }
    }

    public function renderPasswordField($fieldName, array $properties = null) {
        $this->tabIndex+=1;
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $readOnly = ApplicationUtils::getProperty($properties, self::PROPERTY_READONLY);
            $disabled = ApplicationUtils::getProperty($properties, self::PROPERTY_DISABLED);

            if (!empty($readOnly) && $readOnly == true) {
                return "<input type=\"password\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" readonly tabindex=\"{$this->tabIndex}\"/>";
            } elseif (!empty($disabled) && $disabled == true) {
                return "<input type=\"password\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" disabled tabindex=\"{$this->tabIndex}\"/>";
            } else {
                return "<input type=\"text\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\"/>";
            }
        } else {
            return "<input type=\"password\" name=\"{$fieldName}\" tabindex=\"{$this->tabIndex}\"/>";
        }
    }

    public function renderDropDownList($fieldName, array $data, array $properties = null) {
        $tag = "";
        $this->tabIndex+=1;
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $disabled = ApplicationUtils::getProperty($properties, self::PROPERTY_DISABLED);
            $value = ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE);

            if (!empty($disabled) && $disabled == true) {
                $tag = "<select name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\" value=\"{$value}\" disabled>";
            } else {
                $tag = "<select name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\" value=\"{$value}\">";
            }
        } else {
            $tag = "<select name=\"{$fieldName}\" tabindex=\"{$this->tabIndex}\">";
        }
        foreach ($data as $value => $text) {
            /**
             * @todo Handle the option-group property
             */
            if (is_array($text)) {
                
            } else {
                if (!is_null($properties) && ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE) == $value) {
                    $tag.="<option value=\"${value}\" selected>{$text}</option>";
                } else {
                    $tag.="<option value=\"${value}\">{$text}</option>";
                }
            }
        }

        $tag.="</select>";
        return $tag;
    }

    public function renderHiddenField($fieldName, array $properties = null) {
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $value = ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE);

            return "<input type=\"hidden\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" value=\"{$value}\"/>";
        } else {
            return "<input type=\"hidden\" name=\"{$fieldName}\"/>";
        }
    }

    public function renderCheckBox($fieldName, $label, array $properties = null) {
        $this->tabIndex+=1;
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $value = ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE);
            $checked = ApplicationUtils::getProperty($properties, self::PROPERTY_CHECKED);

            if (!empty($checked) && $checked == true) {
                return "<input type=\"checkbox\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" value=\"{$value}\" tabindex=\"{$this->tabIndex}\" checked/>&nbsp;" . $this->renderLabel($label);
            } else {
                return "<input type=\"checkbox\" name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" value=\"{$value}\" tabindex=\"{$this->tabIndex}\"/>&nbsp;" . $this->renderLabel($label);
            }
        } else {
            return "<input type=\"checkbox\" name=\"{$fieldName}\" tabindex=\"{$this->tabIndex}\">&nbsp;" . $this->renderLabel($label);
        }
    }

    public function renderTextArea($fieldName, array $properties = null) {
        $this->tabIndex+=1;
        $tag = "";
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);
            $readOnly = ApplicationUtils::getProperty($properties, self::PROPERTY_READONLY);
            $disabled = ApplicationUtils::getProperty($properties, self::PROPERTY_DISABLED);
            $value = ApplicationUtils::getProperty($properties, self::PROPERTY_VALUE);
            $rows = ApplicationUtils::getProperty($properties, self::PROPERTY_ROWS);

            if (!empty($readOnly) && $readOnly == true) {
                $tag.="<textarea name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" readonly tabindex=\"{$this->tabIndex}\" rows=\"{$rows}\" value=\"{$value}\">";
            } elseif (!empty($disabled) && $disabled == true) {
                $tag.="<textarea name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" disabled tabindex=\"{$this->tabIndex}\" rows=\"{$rows}\" value=\"{$value}\">";
            } else {
                $tag.="<textarea name=\"{$fieldName}\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\" rows=\"{$rows}\" value=\"{$value}\">";
            }
            $tag.=$value;
        } else {
            $tag.="<textarea name=\"{$fieldName}\" tabindex=\"{$this->tabIndex}\">";
        }
        $tag.='</textarea>';
        return $tag;
    }

    public function renderSubmitButton($buttonName, array $properties = null) {
        $this->tabIndex+=1;
        if (!is_null($properties)) {
            $class = ApplicationUtils::getProperty($properties, parent::PROPERTY_CLASS);
            $id = ApplicationUtils::getProperty($properties, parent::PROPERTY_ID);
            $style = ApplicationUtils::getProperty($properties, parent::PROPERTY_STYLE);

            return "<button type=\"submit\" class=\"{$class}\" id=\"{$id}\" style=\"{$style}\" tabindex=\"{$this->tabIndex}\">{$buttonName}</button>";
        } else {
            return "<button type=\"submit\" tabindex=\"{$this->tabIndex}\">{$buttonName}</button>";
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
