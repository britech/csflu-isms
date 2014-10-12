<?php
namespace org\csflu\isms\models\commons;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\uam\Employee as Employee;

/**
 * @property Integer $id
 * @property String $code
 * @property String $name
 * @property Employee $headEmployee
 * @property String $parentDepartmentName
 * 
 * @author britech
 *
 */
class Department extends Model {
	public function validate(){
		
	}
	
	public function bindValuesUsingArray($valuesArray = []){
		
	}
}