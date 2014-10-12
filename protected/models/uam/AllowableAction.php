<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;

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

    public function validate() {
        
    }

    public function bindValuesUsingArray($valuesArray = []) {
        
    }

    public function bindValuesUsingFormData($formData) {
        
    }

}
