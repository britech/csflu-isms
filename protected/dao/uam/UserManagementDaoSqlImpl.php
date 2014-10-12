<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\dao\uam\UserManagementDao as UserManagementDao;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;
use org\csflu\isms\core\ConnectionManager as ConnectionManager;
use org\csflu\isms\models\uam\Employee as Employee;
use org\csflu\isms\models\commons\Department as Department;
use org\csflu\isms\models\uam\Account as Account;
use org\csflu\isms\models\uam\SecurityRole as SecurityRole;

class UserManagementDaoSqlImpl implements UserManagementDao {

    /**
     * (non-PHPdoc)
     * @see \org\csflu\isms\dao\uam\UserManagementDao::authenticate()
     */
    public function authenticate($login) {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT emp_id FROM employees WHERE username=:username AND password=:password AND emp_stat=:stat');
            $dbst->execute(array('username' => $login->username, 'password' => $login->password, 'stat' => 1));

            $employee = new Employee();

            while ($data = $dbst->fetch()) {
                list($employee->id) = $data;
            }

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function listAccounts($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT umain_id, type_desc, dept_name FROM user_main JOIN departments ON dept_ref=dept_id JOIN user_types ON type_ref=utype_id WHERE emp_ref=:ref');
            $dbst->execute(array('ref' => $id));

            $accounts = array();

            while ($data = $dbst->fetch()) {
                $account = new Account();

                $account->securityRole = new SecurityRole();
                $account->employee = new Employee();
                $account->employee->department = new Department();

                list($account->id, $account->securityRole->name, $account->employee->department->name) = $data;

                array_push($accounts, $account);
            }

            return $accounts;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

}
