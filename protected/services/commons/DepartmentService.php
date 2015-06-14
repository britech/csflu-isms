<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\uam\UserAccount;

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
     * @param int $excludeDepartmentId Optional, the department not to be included on the list
     * @param UserAccount[] $accounts Optional, the departments not to be included on the list
     * @return Department
     * @throws ServiceException
     */
    public function listDepartments($excludeDepartmentId = null, array $accounts = null);

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
