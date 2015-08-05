<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\core\DatabaseConnectionManager;
use org\csflu\isms\util\ApplicationLoggerUtils;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\commons\DepartmentDao;
use org\csflu\isms\models\commons\Department;

/**
 * Description of DepartmentDaoSqlImpl
 *
 * @author britech
 */
class DepartmentDaoSqlImpl implements DepartmentDao {

    private $logger;
    private $db;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);

        $connectionManager = DatabaseConnectionManager::getInstance();
        $this->db = $connectionManager->getMainDbConnection();
    }

    public function getDepartmentByCode($code) {
        try {
            $params = array('code' => $code);
            $dbst = $this->db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_code=:code');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $department = new Department();
            while ($data = $dbst->fetch()) {
                list($department->id, $department->code, $department->name) = $data;
            }
            return $department;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getDepartmentById($id) {
        try {
            $params = array('id' => $id);
            $dbst = $this->db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_id=:id');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $department = new Department();
            while ($data = $dbst->fetch()) {
                list($department->id, $department->code, $department->name) = $data;
            }
            return $department;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listDepartments($excludeDepartmentId = null) {
        try {
            if (!is_null($excludeDepartmentId)) {
                $params = array('id'=>$excludeDepartmentId);
                $dbst = $this->db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_id NOT IN (:id) ORDER BY dept_code ASC');
                $dbst->execute($params);
                ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            } else {
                $dbst = $this->db->prepare('SELECT dept_id, dept_code, dept_name FROM departments ORDER BY dept_code ASC');
                $dbst->execute();
                ApplicationLoggerUtils::logSql($this->logger, $dbst);
            }
            

            $departments = array();
            while ($data = $dbst->fetch()) {
                $department = new Department();
                list($department->id, $department->code, $department->name) = $data;
                array_push($departments, $department);
            }
            return $departments;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertDepartment($department) {
        try {
            $this->db->beginTransaction();
            
            $params = array('code'=>$department->code, 'name'=>$department->name);
            $dbst = $this->db->prepare('INSERT INTO departments(dept_code, dept_name) VALUES(:code, :name)');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateDepartment($department) {
        try {
            $this->db->beginTransaction();
            
            $params = array('code'=>$department->code, 'name'=>$department->name, 'id'=>$department->id);
            $dbst = $this->db->prepare('UPDATE departments SET dept_code=:code, dept_name=:name WHERE dept_id=:id');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
