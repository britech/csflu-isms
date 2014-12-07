<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\core\Model;

/**
 * Description of DepartmentController
 *
 * @author britech
 */
class DepartmentController extends Controller {

    private $departmentService;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->departmentService = new DepartmentService();
    }

    public function getDepartmentByCode() {
        $code = filter_input(INPUT_POST, 'code');

        if (isset($code) && !empty($code)) {
            $department = $this->departmentService->getDepartmentDetail(array('code' => $code));
            if (!is_null($department->id)) {
                $this->renderAjaxJsonResponse(array('respCode' => '00', 'id' => $department->id, 'name' => $department->name));
            } else {
                $this->renderAjaxJsonResponse(array('respCode' => '10'));
            }
        } else {
            throw new ControllerException('Data is needed to process request');
        }
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Departments';
        $this->render('department/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Departments' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Add New Department' => array('department/createDepartment'))
                )),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));

        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function renderDepartmentGrid() {
        $departments = $this->departmentService->listDepartments();
        $data = array();
        foreach ($departments as $department) {
            array_push($data, array(
                'name' => $department->code . ' - ' . $department->name,
                'action' => ApplicationUtils::generateLink(
                        array('department/updateDepartment', 'id' => $department->id), 'Update Department')));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function createDepartment() {
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist a Department';
        $this->render('department/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Departments' => array('department/index'),
                'Enlist a Department' => 'active'
            ),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'data' => isset($_SESSION['data']) ? $_SESSION['data'] : ""
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }

        if (isset($_SESSION['data'])) {
            unset($_SESSION['data']);
        }
    }

    public function create() {
        $departmentData = filter_input_array(INPUT_POST)['Department'];

        if (!isset($departmentData) && empty($departmentData)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $department = new Department();
        $department->bindValuesUsingArray(array('department' => $departmentData), $department);
        $department->validationMode = Model::VALIDATION_MODE_INITIAL;

        if ($department->validate()) {
            $this->departmentService->enlistDepartment($department);
            $_SESSION['notif'] = array('class' => 'success', 'message' => 'Department successfully added');
            $this->redirect(array('department/index'));
        } else {
            $_SESSION['validation'] = $department->validationMessages;
            $_SESSION['data'] = $departmentData;
            $this->redirect(array('department/createDepartment'));
        }
    }

    public function updateDepartment() {
        $id = filter_input(INPUT_GET, 'id');
        if(!isset($id) && empty($id)){
            throw new ControllerException('Another parameter is needed to process this request');
        }
        
        $department = $this->departmentService->getDepartmentDetail(array('id'=>$id));
        
        if(is_null($department->id)){
            $_SESSION['notif'] = array('class'=>'', 'message'=>'Department not found');
            $this->redirect(array('department/index'));
        }
        
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist a Department';
        $this->render('department/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Departments' => array('department/index'),
                'Update Department' => 'active'
            ),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'data' => $department
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }
    
    public function update() {
        $departmentData = filter_input_array(INPUT_POST)['Department'];

        if (!isset($departmentData) && empty($departmentData)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $department = new Department();
        $department->bindValuesUsingArray(array('department' => $departmentData), $department);
        $department->validationMode = Model::VALIDATION_MODE_INITIAL;

        if ($department->validate()) {
            $this->departmentService->updateDepartment($department);
            $_SESSION['notif'] = array('class' => 'info', 'message' => 'Department successfully updated');
            $this->redirect(array('department/index'));
        } else {
            $_SESSION['validation'] = $department->validationMessages;
            $_SESSION['data'] = $departmentData;
            $this->redirect(array('department/updateDepartment', 'id'=>$department->id));
        }
    }
    
    public function listDepartments(){
        $departments = $this->departmentService->listDepartments();
        $data = array();
        foreach($departments as $department){
            array_push($data, array(
                'id'=>$department->id, 
                'name'=>'&nbsp;'.$department->code.'&nbsp-&nbsp;'.$department->name));
        }
        $this->renderAjaxJsonResponse($data);
    }

}
