<?php
namespace org\csflu\isms\service\uam;

use org\csflu\isms\models\Login as Login;
use org\csflu\isms\exceptions\ServiceException as ServiceException;


interface UserManagementService {
	
	/**
	 * 
	 * @param Login $login
	 * @throws ServiceException
	 */
	public function authenticate($login);
	
}