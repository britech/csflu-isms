<?php
namespace org\csflu\isms\dao\commons;

use org\csflu\isms\models\commons\Department as Department;

use org\csflu\isms\exceptions\DataAccessException as DataAccessException;
/**
 *
 * @author britech
 */
interface DepartmentDao {
    
    /**
     * @param Integer $excludeDepartmentId
     * @return Department[]
     * @throws DataAccessException
     */
    public function listDepartments($excludeDepartmentId);
    
    /**
     * @param String $code
     * @return Department
     * @throws DataAccessException
     */
    public function getDepartmentByCode($code);
    
    /**
     * @param Integer $id
     * @return Department
     * @throws DataAccessException
     */
    public function getDepartmentById($id);
}
