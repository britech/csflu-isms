<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\commons\Department as Department;
use org\csflu\isms\models\commons\Position as Position;

/**
 * @property Integer $id
 * @property String $lastName
 * @property String $givenName
 * @property Department $department
 * @property Position $position
 * @author britech
 *
 */
class Employee extends Model {

    public function validate() {
        
    }

    public function bindValuesUsingArray($valuesArray = []) {
        
    }

    public function bindValuesUsingFormData($formData) {
        
    }

}
