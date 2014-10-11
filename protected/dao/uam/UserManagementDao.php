<?php
namespace org\csflu\isms\dao\uam;

use org\csflu\isms\models\Login as Login;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;

interface UserManagementDao {
	
	/**
	 * 
	 * @param Login $login
	 * @throws DataAccessException
	 */
	public function authenticate($login);
	
}