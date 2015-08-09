<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\dao\uam\UserManagementDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\core\DatabaseConnectionManager;
use org\csflu\isms\util\ApplicationLoggerUtils;
use org\csflu\isms\util\PDOUtils;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\uam\LoginAccount;
use org\csflu\isms\dao\commons\PositionDaoSqlImpl as PositionDao;
use org\csflu\isms\dao\commons\DepartmentDaoSqlImpl as DepartmentDao;
use org\csflu\isms\dao\uam\SecurityRoleDaoSqlImpl;

class UserManagementDaoSqlImpl implements UserManagementDao {

    private $positionDao;
    private $departmentDao;
    private $securityRoleDao;
    private $db;
    private $hrDb;
    private $logger;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->positionDao = new PositionDao();
        $this->departmentDao = new DepartmentDao();
        $this->securityRoleDao = new SecurityRoleDaoSqlImpl();

        $connectionManager = DatabaseConnectionManager::getInstance();

        $this->db = $connectionManager->getMainDbConnection();
        $this->hrDb = $connectionManager->getHrDbConnection();
    }

    public function authenticate(LoginAccount $loginAccount) {
        try {
            $params = array('username' => $loginAccount->username, 'stat' => LoginAccount::STATUS_ACTIVE);
            $dbst = $this->db->prepare('SELECT emp_id, password FROM employees WHERE username=:username AND emp_stat=:stat');
            $dbst->execute($params);

            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $employee = new Employee();
            $employee->loginAccount = new LoginAccount();

            while ($data = $dbst->fetch()) {
                list($employee->id, $employee->loginAccount->password) = $data;
            }

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage(), $e);
        }
    }

    public function listAccounts($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT umain_id FROM user_main WHERE emp_ref=:ref');
            $dbst->execute(array('ref' => $id));

            $accounts = array();

            while ($data = $dbst->fetch()) {
                list($id) = $data;
                $account = $this->getUserAccount($id);

                array_push($accounts, $account);
            }

            return $accounts;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage());
        }
    }

    public function listEmployees() {
        try {
            $dbst = $this->db->query('SELECT emp_id FROM employees ORDER BY emp_lname ASC, emp_fname ASC');
            ApplicationLoggerUtils::logSql($this->logger, $dbst);
            $employees = array();

            while ($data = $dbst->fetch()) {
                list($id) = $data;
                $employees = array_merge($employees, array($this->getEmployeeData($id)));
            }

            return $employees;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage(), $e);
        }
    }

    public function getEmployeeData($id) {
        try {
            $dbst = $this->db->prepare('SELECT emp_id, emp_lname, emp_fname, emp_mname, position, main_dept, username, emp_stat FROM employees WHERE emp_id=:id');
            $params = array('id' => $id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $employee = new Employee();
            $employee->loginAccount = new LoginAccount();

            while ($data = $dbst->fetch()) {
                list($employee->id,
                        $employee->lastName,
                        $employee->givenName,
                        $employee->middleName,
                        $position,
                        $department,
                        $employee->loginAccount->username,
                        $employee->loginAccount->status) = $data;
            }

            $employee->position = isset($position) ? $this->positionDao->getPositionData($position) : null;
            $employee->department = isset($department) ? $this->departmentDao->getDepartmentById($department) : null;

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage(), $e);
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

    public function insertAccount(UserAccount $account) {
        try {
            PDOUtils::initiateTransaction($this->db);
            $dbst = $this->db->prepare('INSERT INTO employees VALUES(:id, :lname, :fname, :mname, :username, :password, :position, :department, :status)');
            $params = array('id' => $account->employee->id,
                'lname' => $account->employee->lastName,
                'fname' => $account->employee->givenName,
                'mname' => $account->employee->middleName,
                'username' => $account->employee->loginAccount->username,
                'password' => $account->employee->loginAccount->password,
                'position' => $account->employee->position->id,
                'department' => $account->employee->department->id,
                'status' => '1');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $this->linkSecurityRole($account);
            PDOUtils::commitTransaction($this->db);
        } catch (\PDOException $ex) {
            PDOUtils::rollbackTransaction($this->db);
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function validateEmployee($id) {
        try {
            $dbst = $this->hrDb->prepare('SELECT id, fname, mname, lname, dept_code FROM employee LEFT JOIN department ON dept_ref=dept_id WHERE id=:id');
            $params = array('id' => $id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $employee = new Employee();
            $employee->department = new Department();

            while ($data = $dbst->fetch()) {
                list($employee->id, $employee->givenName, $employee->middleName, $employee->lastName, $employee->department->code) = $data;
            }

            return $employee;
        } catch (\PDOException $e) {
            throw new DataAccessException($e->getMessage(), $e);
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

    public function updateLoginAccountStatus(Employee $employee) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE employees SET emp_stat=:stat WHERE emp_id=:id');
            $dbst->execute(array('id' => $employee->id, 'stat' => $employee->loginAccount->status));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getUserAccount($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT umain_id, emp_ref, type_ref, dept_ref, pos_ref FROM user_main WHERE umain_id=:id');
            $dbst->execute(array('id' => $id));

            $account = new UserAccount();

            while ($data = $dbst->fetch()) {
                list($account->id, $employee, $securityRole, $department, $position) = $data;
                $account->employee = $this->getEmployeeData($employee);
                $account->securityRole = $this->securityRoleDao->getSecurityRoleData($securityRole);
                $account->employee->department = $this->departmentDao->getDepartmentById($department);
                $account->employee->position = $this->positionDao->getPositionData($position);
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

    public function getSecurityKey(Employee $employee) {
        try {
            $dbst = $this->db->prepare("SELECT password FROM employees WHERE emp_id=:id");
            $params = array('id' => $employee->id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            while ($data = $dbst->fetch()) {
                list($password) = $data;
            }

            return $password;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateSecurityKey(Employee $employee) {
        try {
            PDOUtils::initiateTransaction($this->db);
            $dbst = $this->db->prepare("UPDATE employees SET password=:password WHERE emp_id=:id");
            $params = array('password' => $employee->loginAccount->password, 'id' => $employee->id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            PDOUtils::commitTransaction($this->db);
        } catch (\PDOException $ex) {
            PDOUtils::rollbackTransaction($this->db);
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function linkSecurityRole(UserAccount $userAccount) {
        try {
            PDOUtils::initiateTransaction($this->db);
            $dbst = $this->db->prepare('INSERT INTO user_main(emp_ref, type_ref, dept_ref, pos_ref) VALUES(:employee, :type, :department, :position)');
            $params = array('employee' => $userAccount->employee->id,
                'type' => $userAccount->securityRole->id,
                'department' => $userAccount->employee->department->id,
                'position' => $userAccount->employee->position->id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            PDOUtils::commitTransaction($this->db);
        } catch (\PDOException $ex) {
            PDOUtils::rollbackTransaction($this->db);
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function updateUserAccount(UserAccount $userAccount) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE user_main SET type_ref=:type, pos_ref=:position WHERE umain_id=:id');
            $dbst->execute(array(
                'type' => $userAccount->securityRole->id,
                'position' => $userAccount->employee->position->id,
                'id' => $userAccount->id));

            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listAccountsByDepartmentReference(Department $department) {
        try {
            $dbst = $this->db->prepare('SELECT umain_id FROM user_main WHERE dept_ref=:department');
            $dbst->execute(array(
                'department' => $department->id
            ));

            $accounts = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                $accounts = array_merge($accounts, array($this->getUserAccount($id)));
            }
            return $accounts;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listAccountsByEmployeeReference(Employee $employee) {
        try {
            $dbst = $this->db->prepare('SELECT umain_id FROM user_main WHERE emp_ref=:employee');
            $dbst->execute(array(
                'employee' => $employee->id
            ));

            $accounts = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                $accounts = array_merge($accounts, array($this->getUserAccount($id)));
            }
            return $accounts;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
