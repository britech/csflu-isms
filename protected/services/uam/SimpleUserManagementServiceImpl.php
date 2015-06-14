<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl as UserManagementDao;
use org\csflu\isms\dao\uam\SecurityRoleDaoSqlImpl as SecurityRoleDao;
use org\csflu\isms\service\uam\UserManagementService;
use org\csflu\isms\exceptions\ServiceException as ServiceException;

class SimpleUserManagementServiceImpl implements UserManagementService {

    private $userDaoSource;
    private $securityRoleDaoSource;

    public function __construct() {
        $this->userDaoSource = new UserManagementDao();
        $this->securityRoleDaoSource = new SecurityRoleDao();
    }

    public function authenticate($login) {
        return $this->userDaoSource->authenticate($login);
    }

    public function listAccounts(Employee $employee = null, Department $department = null) {
        if (!is_null($employee)) {
            $accounts = $this->userDaoSource->listAccountsByEmployeeReference($employee);
        } elseif (!is_null($department)) {
            $accounts = $this->userDaoSource->listAccountsByDepartmentReference($department);
        }
        
        if (count($accounts) == 0) {
            throw new ServiceException('Account Setup is invalid');
        }
        return $accounts;
    }

    public function listEmployees() {
        return $this->userDaoSource->listEmployees();
    }

    public function getEmployeeData($id) {
        return $this->userDaoSource->getEmployeeData($id);
    }

    public function listSecurityRoles() {
        $roles = $this->securityRoleDaoSource->listSecurityRoles();

        if (count($roles) == 0) {
            throw new ServiceException('Security Roles not defined');
        }
        return $roles;
    }

    public function createAccount($account) {
        $this->userDaoSource->insertAccount($account);
    }

    public function validateEmployee($id) {
        return $this->userDaoSource->validateEmployee($id);
    }

    public function getLoginAccountStatus($id) {
        $status = $this->userDaoSource->getLoginAccountStatus($id);

        if (is_null($status)) {
            throw new ServiceException('Account setup is invalid');
        }
        return $status;
    }

    public function resetPassword(Employee $employee) {
        $this->userDaoSource->resetPassword($employee);
    }

    public function updateLoginAccountStatus(Employee $employee) {
        $this->userDaoSource->updateLoginAccountStatus($employee);
    }

    public function getAccountById($id) {
        return $this->userDaoSource->getUserAccount($id);
    }

    public function unlinkSecurityRole($id) {
        $account = $this->userDaoSource->getUserAccount($id);
        $employee = $this->userDaoSource->getEmployeeData($account->employee->id);
        $accounts = $this->userDaoSource->listAccounts($account->employee->id);

        if ($account->employee->department->id == $employee->department->id) {
            throw new ServiceException('Default linked security role cannot be deleted');
        }

        if (count($accounts) == 1) {
            throw new ServiceException('At least one security role must be assigned to an account');
        }
        $this->userDaoSource->unlinkSecurityRole($id);
    }

    public function getSecurityKey($id) {
        return $this->userDaoSource->getSecurityKey($id);
    }

    public function updateSecurityKey($employee) {
        $this->userDaoSource->updateSecurityKey($employee);
    }

    public function linkSecurityRole($account) {
        $this->userDaoSource->linkSecurityRole($account);
    }

    public function getSecurityRoleData($id) {
        return $this->securityRoleDaoSource->getSecurityRoleData($id);
    }

    public function updateSecurityRole($securityRole) {
        $this->securityRoleDaoSource->updateRoleDescription($securityRole);
        $this->securityRoleDaoSource->manageLinkedActions($securityRole);
    }

    public function enlistSecurityRole($securityRole) {
        return $this->securityRoleDaoSource->enlistSecurityRole($securityRole);
    }

    public function removeSecurityRole($securityRole) {
        $this->securityRoleDaoSource->deleteSecurityRole($securityRole);
    }

    public function updateAccount($userAccount) {
        $this->userDaoSource->updateUserAccount($userAccount);
    }

}
