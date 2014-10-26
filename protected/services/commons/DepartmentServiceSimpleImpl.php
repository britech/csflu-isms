<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\service\commons\DepartmentService;
use org\csflu\isms\dao\commons\DepartmentDaoSqlImpl as DepartmentDao;
use org\csflu\isms\exceptions\ServiceException;
/**
 * Description of DepartmentServiceSimpleImpl
 *
 * @author britech
 */
class DepartmentServiceSimpleImpl implements DepartmentService{
    
    private $daoSource = null;
    public function __construct() {
        $this->daoSource = new DepartmentDao();
    }

    public function getDepartmentDetail(array $params) {
        if(array_key_exists('id', $params)){
            return $this->daoSource->getDepartmentById($params['id']);
        } else if(array_key_exists('code', $params)){
            return $this->daoSource->getDepartmentByCode($params['code']);
        } else{
            throw new ServiceException('Identifier expected');
        }
    }

    public function listDepartments($excludeDepartmentId = null) {
        if(!is_null($excludeDepartmentId)){
            $departments = $this->daoSource->listDepartments($excludeDepartmentId);
        } else {
            $departments = $this->daoSource->listDepartments();
        }
        if(count($departments) == 0){
            throw new ServiceException('No departments defined');
        }
        return $departments;
    }

    public function enlistDepartment($department) {
        $this->daoSource->insertDepartment($department);
    }

    public function updateDepartment($department) {
        $this->daoSource->updateDepartment($department);
    }

}
