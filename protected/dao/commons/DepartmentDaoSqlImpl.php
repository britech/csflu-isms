<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\commons\DepartmentDao;
use org\csflu\isms\models\commons\Department;

/**
 * Description of DepartmentDaoSqlImpl
 *
 * @author britech
 */
class DepartmentDaoSqlImpl implements DepartmentDao {

    public function getDepartmentByCode($code) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $dbst = $db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_code=:code');
            $dbst->execute(array('code' => $code));

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
        $db = ConnectionManager::getConnectionInstance();
        try {
            $dbst = $db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_id=:id');
            $dbst->execute(array('id' => $id));

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
        $db = ConnectionManager::getConnectionInstance();
        try {
            if (!is_null($excludeDepartmentId)) {
                $dbst = $db->prepare('SELECT dept_id, dept_code, dept_name FROM departments WHERE dept_id NOT IN (:id) ORDER BY dept_code ASC');
                $dbst->execute(array('id'=>$excludeDepartmentId));
            } else {
                $dbst = $db->prepare('SELECT dept_id, dept_code, dept_name FROM departments ORDER BY dept_code ASC');
                $dbst->execute();
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
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            
            $dbst = $db->prepare('INSERT INTO departments(dept_code, dept_name) VALUES(:code, :name)');
            $dbst->execute(array('code'=>$department->code, 'name'=>$department->name));
            
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateDepartment($department) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            
            $dbst = $db->prepare('UPDATE departments SET dept_code=:code, dept_name=:name WHERE dept_id=:id');
            $dbst->execute(array('code'=>$department->code, 'name'=>$department->name, 'id'=>$department->id));
            
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
