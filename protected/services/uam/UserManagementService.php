<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\models\uam\Login;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\exceptions\ServiceException;

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
     * @param Employee $employee List according to the Employee entity
     * @param Department $department List according to the Department entity
     * @return UserAccount[]
     * @throws ServiceException
     */
    public function listAccounts(Employee $employee = null, Department $department = null);

    /**
     * Retrieve all employees
     * @return Employee[]
     * @throws ServiceException
     */
    public function listEmployees();

    /**
     * Gets the employee via ID
     * @param Integer $id
     * @return Employee
     * @throws ServiceException
     */
    public function getEmployeeData($id);

    /**
     * Validates an employee via ID.
     * @param Integer $id
     * @return Employee
     * @throws ServiceException
     */
    public function validateEmployee($id);

    /**
     * Retrieves the available security roles in the ISMS environment
     * @return SecurityRole[]
     * @throws ServiceException
     */
    public function listSecurityRoles();

    /**
     * Creates a new Account
     * @param UserAccount $account
     * @return String
     * @throws ServiceException
     */
    public function createAccount($account);

    /**
     * Retrieves the current status of the login account
     * @param Integer $id Employee ID
     * @return Integer
     * @throws ServiceException
     */
    public function getLoginAccountStatus($id);

    /**
     * Resets the password of the login account to default (username)
     * @param Employee $employee
     * @throws ServiceException
     */
    public function resetPassword(Employee $employee);

    /**
     * Disable/Enable a login account
     * @param Integer $id Employee ID
     * @param Integer $status
     * @throws ServiceException
     */
    public function updateLoginAccountStatus($id, $status);

    /**
     * Retrieves the account information via User ID
     * @param Integer $id
     * @return UserAccount
     * @throws ServiceException
     */
    public function getAccountById($id);

    /**
     * Deletes the linked security role on the login account
     * @param Integer $id
     * @throws ServiceException
     */
    public function unlinkSecurityRole($id);

    /**
     * Gets the security key (password)
     * @param Integer $id Employee ID
     * @return String
     * @throws ServiceException
     */
    public function getSecurityKey($id);

    /**
     * Updates the security key (password)
     * @param Employee $employee
     * @throws ServiceException
     */
    public function updateSecurityKey($employee);

    /**
     * Links a security role on an UserAccount
     * @param UserAccount $account
     * @throws ServiceException
     */
    public function linkSecurityRole($account);

    /**
     * Gets the information of the selected Security Role
     * @param Integer $id
     * @return SecurityRole
     * @throws ServiceException;
     */
    public function getSecurityRoleData($id);

    /**
     * Updates the Security Role
     * @param SecurityRole $securityRole
     * @throws ServiceException
     */
    public function updateSecurityRole($securityRole);

    /**
     * Creates a new security role
     * @param SecurityRole $securityRole
     * @throws ServiceException
     */
    public function enlistSecurityRole($securityRole);

    /**
     * Deletes the security role
     * @param SecurityRole $securityRole
     * @throws ServiceException
     */
    public function removeSecurityRole($securityRole);

    /**
     * Updates an account
     * @param UserAccount $userAccount
     * @throws ServiceException
     */
    public function updateAccount($userAccount);
}
