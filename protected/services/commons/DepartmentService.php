<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\commons\Department;
/**
 *
 * @author britech
 */
interface DepartmentService {
    
    /**
     * Retrieves the department detail via array.
     * Allowed parameter keys are 'id' or 'code'
     * @param array $params
     * @return Department
     * @throws ServiceException
     */
    public function getDepartmentDetail(array $params);
    
    /**
     * Retrieves the defined departments
     * @param Integer $excludeDepartmentId Optional, the department not to be included on the list
     * @return Department
     * @throws ServiceException
     */
    public function listDepartments($excludeDepartmentId = null);
    
    /**
     * Enlists a department
     * @param Department $department
     * @throws ServiceException
     */
    public function enlistDepartment($department);
    
    /**
     * Updates a department
     * @param Department $department
     * @throws ServiceException
     */
    public function updateDepartment($department);
}
