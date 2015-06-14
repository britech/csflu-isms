<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\models\uam\LoginAccount;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\exceptions\DataAccessException;

interface UserManagementDao {

    /**
     * 
     * @param LoginAccount $loginAccount
     * @return Employee
     * @throws DataAccessException
     */
    public function authenticate(LoginAccount $loginAccount);

    /**
     * @param Department $department
     * @return UserAccount[]
     * @throws DataAccessException
     */
    public function listAccountsByDepartmentReference(Department $department);

    /**
     * @param Employee $employee
     */
    public function listAccountsByEmployeeReference(Employee $employee);

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
     * @param Employee $employee
     * @throws DataAccessException
     */
    public function resetPassword(Employee $employee);

    /**
     * @param Employee $employee
     * @throws DataAccessException
     */
    public function updateLoginAccountStatus(Employee $employee);

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
     * @param Employee $employee
     * @return String
     * @throws DataAccessException
     */
    public function getSecurityKey(Employee $employee);

    /**
     * @param Employee $employee
     * @throws DataAccessException
     */
    public function updateSecurityKey($employee);

    /**
     * @param UserAccount $userAccount
     * @throws DataAccessException
     */
    public function linkSecurityRole(UserAccount $userAccount);

    /**
     * @param UserAccount $userAccount
     * @throws DataAccessException
     */
    public function updateUserAccount($userAccount);
}
