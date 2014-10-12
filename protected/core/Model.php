<?php

namespace org\csflu\isms\core;

abstract class Model {

    /**
     * 
     * @return boolean
     */
    abstract function validate();

    abstract function bindValuesUsingArray($valuesArray = []);

    public function getAttributeNames() {
        return array();
    }

}
