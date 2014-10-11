<?php
namespace org\csflu\isms\dao\uam;

use org\csflu\isms\dao\uam\UserManagementDao as UserManagementDao;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;

use org\csflu\isms\models\Login as Login;

class UserManagementDaoDummyImpl implements UserManagementDao{
	
	private $users = array('admin'=>'admin', 'test'=>'test');
	
	/**
	 * (non-PHPdoc)
	 * @see \org\csflu\isms\dao\uam\UserManagementDao::authenticate()
	 */
	public function authenticate($login){
		if(array_key_exists($login->username, $this->users)){
			$newLogin = new Login();
			$newLogin->username = $login;
		} else{
			throw new DataAccessException('Datasource failure', '5');
		}
	}
}