<?php
namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\uam\SecurityRole as SecurityRole;
use org\csflu\isms\models\uam\Employee as Employee;
/**
 * @property Integer $id
 * @property String $username
 * @property String $password
 * @property Integer $accountStatus
 * @property Employee $employee
 * @property SecurityRole $securityRole
 * 
 * @author britech
 *
 */
class Account extends Model{
    public function validate(){
		
    }

    public function bindValuesUsingArray($valuesArray = []){
		
    }

    public function bindValuesUsingFormData($formData){
		
    }
}