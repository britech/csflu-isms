<?php
namespace org\csflu\isms\service\uam;


use org\csflu\isms\dao\uam\UserManagementDaoDummyImpl as UserManagementDao;
use org\csflu\isms\core\exceptions\DataAccessException as DataAccessException;

use org\csflu\isms\service\uam\UserManagementService as UserManagementService;
use org\csflu\isms\core\exceptions\ServiceException as ServiceException;

class SimpleUserManagementServiceImpl implements UserManagementService {
	
	private $daoSource;
	
	public function __construct(){
		$this->daoSource = new UserManagementDao();
	}
	
	public function authenticate($login){
		try{
			$this->daoSource->authenticate($login);
		} catch(DataAccessException $e){
			throw new ServiceException($e->getMessage(), $e->getCode(), $e->getPrevious());
		}
	}
}