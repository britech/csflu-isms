<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl as UserManagementDao;
use org\csflu\isms\service\uam\UserManagementService as UserManagementService;
use org\csflu\isms\exceptions\ServiceException as ServiceException;

class SimpleUserManagementServiceImpl implements UserManagementService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new UserManagementDao();
    }

    public function authenticate($login) {
        return $this->daoSource->authenticate($login);
    }

    public function listAccounts($id) {
        $accounts = $this->daoSource->listAccounts($id);
        
        if(count($accounts) == 0){
            throw new ServiceException('Account Setup is invalid');
        }
        return $accounts;
    }

}
