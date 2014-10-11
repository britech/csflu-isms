<?php
namespace org\csflu\isms\models\uam;

/**
 * @property String $username
 * @property String $password
 * @author britech
 *
 */
class Login {
	
	public function isValid(){
		if(empty($this->username) || empty($this->password)){
			return false;
		} else{
			return true;
		}
	}
	
	public function bindValuesUsingArray($valuesArray = []){
		$this->username = $valuesArray['username'];
		$this->password = $valuesArray['password'];
	}
}