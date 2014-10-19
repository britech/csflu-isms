<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl as UserManagementDao;
use org\csflu\isms\service\uam\UserManagementService;
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

    public function listEmployees() {
        return $this->daoSource->listEmployees();
    }

    public function getEmployeeData($id) {
        return $this->daoSource->getEmployeeData($id);
    }

    public function listSecurityRoles() {
        $roles = $this->daoSource->listSecurityRoles();
        
        if(count($roles) == 0){
            throw new ServiceException('Security Roles not defined');
        }
        return $roles;
    }

    public function createAccount($account) {
        $this->daoSource->insertAccount($account);
    }

    public function validateEmployee($id) {
        return $this->daoSource->validateEmployee($id);
    }

    public function getLoginAccountStatus($id) {
        $status = $this->daoSource->getLoginAccountStatus($id);
        
        if(is_null($status)){
            throw new ServiceException('Account setup is invalid');
        }
        return $status;
    }

    public function resetPassword($id) {
        $this->daoSource->resetPassword($id);
    }

    public function updateLoginAccountStatus($id, $status) {
        $this->daoSource->updateLoginAccountStatus($id, $status);
    }

    public function getAccountById($id) {
        return $this->daoSource->getUserAccount($id);
    }
    
    public function unlinkSecurityRole($id){
        $account = $this->daoSource->getUserAccount($id);
        
        $accounts = $this->daoSource->listAccounts($account->employee->id);
        
        if(count($accounts) == 1){
            throw new ServiceException('At least one security role must be assigned to an account.');
        }
        $this->daoSource->unlinkSecurityRole($id);
    }

    public function getSecurityKey($id) {
        return $this->daoSource->getSecurityKey($id);
    }

    public function updateSecurityKey($employee) {
        $this->daoSource->updateSecurityKey($employee);
    }

    public function linkSecurityRole($account) {
        $this->daoSource->linkSecurityRole($account);
    }

}
