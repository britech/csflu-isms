<?php
namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\uam\AllowableAction as AllowableAction;
/**
 * @property Integer $id
 * @property String $name
 * @property String $description
 * @property AllowableAction[] $allowableActions;
 * @author britech
 *
 */
class SecurityRole extends Model {
    public function validate(){
		
    }
	
    public function bindValuesUsingArray($valuesArray = []){
		
    }
}