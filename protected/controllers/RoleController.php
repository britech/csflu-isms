<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;

/**
 * Description of RoleController
 *
 * @author britech
 */
class RoleController extends Controller {

    private $userService;
    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->userService = new UserManagementService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Security Role';
        $this->render('user/listRole', array(
            'breadcrumb' => array('Home' => array('site/index'), 'Security Roles' => 'active'),
            'sidebar' => array('data'=>$this->getSidebarData())));
    }

    private function getSidebarData() {
        return array('header' => 'User Management',
            'links' => array('Account Maintenance' => array('user/index'),
                'Security Role' => array('role/index')));
    }
    
    public function renderSecurityRoleGrid(){
        $roles = $this->userService->listSecurityRoles();
        
        $data = array();
        
        foreach($roles as $role){
            array_push($data, array('name'=>$role->description, 'action'=>ApplicationUtils::generateLink(array('role/updateRole', 'id'=>$role->id), 'Update Details')));
        }
        $this->renderAjaxJsonResponse($data);
    }
}
