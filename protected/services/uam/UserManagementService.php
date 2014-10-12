<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\models\uam\Login as Login;
use org\csflu\isms\models\uam\Employee as Employee;
use org\csflu\isms\models\uam\Account as Account;
use org\csflu\isms\exceptions\ServiceException as ServiceException;

interface UserManagementService {

    /**
     * Authenticates the user credential (username and password)
     * @param Login $login
     * @return Employee
     * @throws ServiceException
     */
    public function authenticate($login);
    
    /**
     * Retrieves the available account types for the employee to enter the system
     * @param Integer $id
     * @return Account[]
     * @throws ServiceException
     */
    public function listAccounts($id);
}
