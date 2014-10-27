<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\dao\uam\UserManagementDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\Position;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\uam\LoginAccount;

class UserManagementDaoSqlImpl implements UserManagementDao {

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

            $dbst = $db->prepare('SELECT umain_id, type_desc, dept_name, type_name, dept_name, pos_desc'
                    . ' FROM user_main '
                    . ' JOIN departments ON dept_ref=dept_id'
                    . ' JOIN user_types ON type_ref=utype_id'
                    . ' JOIN positions ON pos_ref=pos_id'
                    . ' WHERE emp_ref=:ref');
            $dbst->execute(array('ref' => $id));

            $accounts = array();

            while ($data = $dbst->fetch()) {
                $account = new UserAccount();

                $account->securityRole = new SecurityRole();
                $account->employee = new Employee();
                $account->employee->department = new Department();
                $account->employee->position = new Position();

                list($account->id,
                        $account->securityRole->name,
                        $account->employee->department->name,
                        $account->securityRole->name,
                        $account->employee->department->name,
                        $account->employee->position->name) = $data;

                array_push($accounts, $account);
            }

            return $accounts;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function listEmployees() {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT emp_id, emp_lname, emp_fname, emp_stat FROM employees ORDER BY emp_lname ASC, emp_fname ASC');
            $dbst->execute();

            $employees = array();

            while ($data = $dbst->fetch()) {
                $employee = new Employee();
                $employee->loginAccount = new LoginAccount();
                list($employee->id, $employee->lastName, $employee->givenName, $employee->loginAccount->status) = $data;

                array_push($employees, $employee);
            }

            return $employees;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function getEmployeeData($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT emp_id, emp_lname, emp_fname, emp_mname, position, main_dept, dept_name, username, emp_stat'
                    . ' FROM employees'
                    . ' LEFT JOIN departments ON main_dept=dept_id'
                    . ' WHERE emp_id=:id');
            $dbst->execute(array('id' => $id));

            $department = new Department();
            $position = new Position();

            $employee = new Employee();
            $employee->department = $department;
            $employee->position = $position;
            $employee->loginAccount = new LoginAccount();

            while ($data = $dbst->fetch()) {
                list($employee->id,
                        $employee->lastName,
                        $employee->givenName,
                        $employee->middleName,
                        $employee->position->id,
                        $employee->department->id,
                        $employee->department->name,
                        $employee->loginAccount->username,
                        $employee->loginAccount->status) = $data;
            }

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function listSecurityRoles() {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT utype_id, type_name, type_desc FROM user_types ORDER BY type_desc ASC');
            $dbst->execute();

            $roles = array();

            while ($data = $dbst->fetch()) {
                $role = new SecurityRole();
                list($role->id, $role->name, $role->description) = $data;
                array_push($roles, $role);
            }

            return $roles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    /**
     * 
     * @param UserAccount $account
     * @return String
     * @throws DataAccessException
     */
    public function insertAccount($account) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO employees VALUES(:id, :lname, :fname, :mname, :username, :password, :position, :department, :status)');
            $dbst->execute(array('id' => $account->employee->id,
                'lname' => $account->employee->lastName,
                'fname' => $account->employee->givenName,
                'mname' => $account->employee->middleName,
                'username' => $account->employee->loginAccount->username,
                'password' => $account->employee->loginAccount->password,
                'position' => $account->employee->position->id,
                'department' => $account->employee->department->id,
                'status' => '1'));
            $db->commit();
            $this->linkSecurityRole($account);
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function validateEmployee($id) {
        try {
            $db = ConnectionManager::getHrConnectionInstance();

            $dbst = $db->prepare('SELECT id, fname, mname, lname, dept_code FROM employee LEFT JOIN department ON dept_ref=dept_id WHERE id=:id');
            $dbst->execute(array('id' => $id));

            $department = new Department();

            $employee = new Employee();
            $employee->department = $department;

            while ($data = $dbst->fetch()) {
                list($employee->id, $employee->givenName, $employee->middleName, $employee->lastName, $employee->department->code) = $data;
            }

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function getLoginAccountStatus($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT emp_stat FROM employees WHERE emp_id=:id');
            $dbst->execute(array('id' => $id));

            while ($data = $dbst->fetch()) {
                list($status) = $data;
            }
            return $status;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function resetPassword($id) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE employees SET password = username WHERE emp_id=:id');
            $dbst->execute(array('id' => $id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateLoginAccountStatus($id, $status) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE employees SET emp_stat=:stat WHERE emp_id=:id');
            $dbst->execute(array('id' => $id, 'stat' => $status));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getUserAccount($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT umain_id, emp_ref, type_ref, dept_ref, pos_ref, emp_lname, emp_fname, emp_stat, type_desc, dept_name, username'
                    . ' FROM user_main'
                    . ' JOIN employees ON emp_ref = emp_id'
                    . ' JOIN user_types ON type_ref = utype_id'
                    . ' JOIN departments ON dept_ref = dept_id'
                    . ' WHERE umain_id=:id');
            $dbst->execute(array('id' => $id));

            $account = new UserAccount();
            $account->employee = new Employee();
            $account->employee->department = new Department();
            $account->employee->position = new Position();
            $account->employee->loginAccount = new LoginAccount();
            $account->securityRole = new SecurityRole();

            while ($data = $dbst->fetch()) {
                list($account->id,
                        $account->employee->id,
                        $account->securityRole->id,
                        $account->employee->department->id,
                        $account->employee->position->id,
                        $account->employee->lastName,
                        $account->employee->givenName,
                        $account->employee->loginAccount->status,
                        $account->securityRole->description,
                        $account->employee->department->name,
                        $account->employee->loginAccount->username) = $data;
            }

            return $account;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function unlinkSecurityRole($id) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare("DELETE FROM user_main WHERE umain_id=:id");
            $dbst->execute(array('id' => $id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getSecurityKey($id) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $dbst = $db->prepare("SELECT password FROM employees WHERE emp_id=:id");
            $dbst->execute(array('id' => $id));

            while ($data = $dbst->fetch()) {
                list($password) = $data;
            }

            return $password;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateSecurityKey($employee) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare("UPDATE employees SET password=:password WHERE emp_id=:id");
            $dbst->execute(array('password' => $employee->loginAccount->password, 'id' => $employee->id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function linkSecurityRole($userAccount) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO user_main(emp_ref, type_ref, dept_ref, pos_ref) VALUES(:employee, :type, :department, :position)');
            $dbst->execute(array('employee' => $userAccount->employee->id,
                'type' => $userAccount->securityRole->id,
                'department' => $userAccount->employee->department->id,
                'position' => $userAccount->employee->position->id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateUserAccount($userAccount) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE user_main SET type_ref=:type, pos_ref=:position WHERE umain_id=:id');
            $dbst->execute(array(
                'type'=>$userAccount->securityRole->id,
                'position'=>$userAccount->employee->position->id,
                'id'=>$userAccount->id));
            
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
