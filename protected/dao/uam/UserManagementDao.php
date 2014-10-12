<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\models\uam\Login as Login;
use org\csflu\isms\models\uam\Employee as Employee;
use org\csflu\isms\models\uam\Account as Account;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;

interface UserManagementDao {

    /**
     * 
     * @param Login $login
     * @return Employee
     * @throws DataAccessException
     */
    public function authenticate($login);
    
    /**
     * @param Integer $id
     * @return Account[]
     * @return DataAccessException
     */
    public function listAccounts($id);
}
