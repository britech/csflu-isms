<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller as Controller;
use org\csflu\isms\exceptions\ValidationException as ValidationException;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
/**
 * Description of DepartmentController
 *
 * @author britech
 */
class DepartmentController extends Controller{
    
    private $departmentService;
    
    public function __construct() {
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
    
}
