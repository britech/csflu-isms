<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\models\uam\Login;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\exceptions\DataAccessException;

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
     * @return UserAccount[]
     * @throws DataAccessException
     */
    public function listAccounts($id);
    
    /**
     * @return Employee[]
     * @throws DataAccessException
     */
    public function listEmployees();

    /**
     * @return Employee
     * @throws DataAccessException
     */
    public function getEmployeeData($id);
    
    /**
     * @return Employee
     * @throws DataAccessException
     */
    public function validateEmployee($id);
    
    /**
     * @return SecurityRole[]
     * @throws DataAccessException
     */
    public function listSecurityRoles();
    
    /**
     * @param UserAccount $account
     * @return String
     * @throws DataAccessException
     */
    public function insertAccount($account);
    
    /**
     * @param Integer $id
     * @return Integer
     * @throws DataAccessException
     */
    public function getLoginAccountStatus($id);
    
    /**
     * @param Integer $id
     * @throws DataAccessException
     */
    public function resetPassword($id);
    
    /**
     * @param Integer $id
     * @param Integer $status
     * @throws DataAccessException
     */
    public function updateLoginAccountStatus($id, $status);
    
    /**
     * @param Integer $id
     * @return UserAccount
     * @throws DataAccessException
     */
    public function getUserAccount($id);
    
    /**
     * @param Integer $id
     * @throws DataAccessException
     */
    public function unlinkSecurityRole($id);
    
    /**
     * @param Integer $id
     * @return String
     * @throws DataAccessException
     */
    public function getSecurityKey($id);
    
    /**
     * @param Employee $employee
     * @throws DataAccessException
     */
    public function updateSecurityKey($employee);
    
    /**
     * @param UserAccount $userAccount
     * @throws DataAccessException
     */
    public function linkSecurityRole($userAccount);
    
    /**
     * @param UserAccount $userAccount
     * @throws DataAccessException
     */
    public function updateUserAccount($userAccount);
}
