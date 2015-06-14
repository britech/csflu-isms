<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\uam\Employee;

/**
 * @property Integer $id
 * @property Employee $employee
 * @property SecurityRole $securityRole
 * 
 * @author britech
 *
 */
class UserAccount extends Model {

    private $id;
    private $employee;
    private $securityRole;

    public function validate() {
        
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('employee', $valueArray)) {
            $this->employee = new Employee();
            $this->employee->bindValuesUsingArray($valueArray);
        }

        if (array_key_exists('securityrole', $valueArray)) {
            $this->securityRole = new SecurityRole();
            $this->securityRole->bindValuesUsingArray($valueArray, $this->securityRole);
        }
        
        if(array_key_exists('useraccount', $valueArray)){
            parent::bindValuesUsingArray($valueArray, $this);
        }
    }

    public function getAttributeNames() {
        return array(
            'securityRole' => 'Security Role'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
