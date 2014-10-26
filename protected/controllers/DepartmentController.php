<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ValidationException as ValidationException;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
/**
 * Description of DepartmentController
 *
 * @author britech
 */
class DepartmentController extends Controller{
    
    private $departmentService;
    
    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->departmentService = new DepartmentService();
    }
    
    public function getDepartmentByCode(){
        $code = filter_input(INPUT_POST, 'code');
        
        if(isset($code) && !empty($code)){
            $department = $this->departmentService->getDepartmentDetail(array('code'=>$code));
            if(!is_null($department->id)){
                $this->renderAjaxJsonResponse(array('respCode'=>'00','id'=>$department->id, 'name'=>$department->name));
            } else{
                $this->renderAjaxJsonResponse(array('respCode'=>'10'));
            }
        } else{
            throw new ValidationException('Data is needed to process request');
        }
    }
    
    public function index(){
        $this->title = ApplicationConstants::APP_NAME.' - Manage Departments';
        $this->render('department/index', array(
            'breadcrumb'=>array(
                'Home'=>array('site/index'),
                'Manage Departments'=>'active'),
            'sidebar'=>array(
                'data'=>array(
                    'header'=>'Actions',
                    'links'=>array(
                        'Add New Department' => array('role/createDepartment')
                    )
                )
            )
        ));
    }
    
    public function listDepartments(){
        $departments = $this->departmentService->listDepartments();
        $data = array();
        foreach($departments as $department) {
            array_push($data, array(
                'name'=>$department->code.' - '.$department->name,
                'action'=>ApplicationUtils::generateLink(
                        array('department/updateDepartment', 'id'=>$department->id), 
                        'Update Department')));
        }
        $this->renderAjaxJsonResponse($data);
    }
}
