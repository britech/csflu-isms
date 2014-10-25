<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;
use org\csflu\isms\exceptions\ValidationException;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\uam\SecurityRole;

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
            'breadcrumb' => array(
                'Home' => array('site/index'), 
                'Security Roles' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData()),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    private function getSidebarData() {
        return array('header' => 'User Management',
            'links' => array('Account Maintenance' => array('user/index'),
                'Security Role' => array('role/index')));
    }

    public function renderSecurityRoleGrid() {
        $roles = $this->userService->listSecurityRoles();

        $data = array();

        foreach ($roles as $role) {
            array_push($data, array(
                'id' => $role->id,
                'name' => $role->description,
                'action' => ApplicationUtils::generateLink(array('role/updateRole', 'id' => $role->id), 'Update Details')));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function updateRole() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $securityRole = $this->userService->getSecurityRoleData($id);

        $this->title = ApplicationConstants::APP_NAME . ' - Update Security Role';
        $this->render('user/updateRole', array(
            'breadcrumb' => array('Home' => array('site/index'), 'Security Roles' => array('role/index'), 'Update Security Role' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData()),
            'model' => new ModuleAction(),
            'roleDescription' => $securityRole->description,
            'id' => $securityRole->id,
            'actions' => $securityRole->allowableActions,
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function update() {
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];
        $allowableActionRoleData = filter_input_array(INPUT_POST)['AllowableAction'];

        $condition = (isset($securityRoleData) && !empty($securityRoleData)) || (isset($allowableActionRoleData) && !empty($allowableActionRoleData));

        if (!$condition) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $securityRole = new SecurityRole();
        $securityRole->bindValuesUsingArray(array('securityrole' => $securityRoleData, 'moduleactions' => $allowableActionRoleData));
        
        $this->userService->updateSecurityRole($securityRole);
        $_SESSION['notif'] = "Security Role successfully updated";
        $this->redirect(array('role/updateRole', 'id'=>$securityRole->id));
    }
    
    public function createRole(){
        $this->title = ApplicationConstants::APP_NAME . ' - Add Security Role';
        $this->render('user/createRole', array(
            'breadcrumb' => array('Home' => array('site/index'), 
                'Security Roles' => array('role/index'), 
                'Add Security Role' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData()),
            'model' => new ModuleAction()
        ));
    }
    
    public function create(){
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];
        $allowableActionRoleData = filter_input_array(INPUT_POST)['AllowableAction'];

        $condition = (isset($securityRoleData) && !empty($securityRoleData)) || (isset($allowableActionRoleData) && !empty($allowableActionRoleData));

        if (!$condition) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $securityRole = new SecurityRole();
        $securityRole->bindValuesUsingArray(array('securityrole' => $securityRoleData, 'moduleactions' => $allowableActionRoleData));
        $id = $this->userService->enlistSecurityRole($securityRole);
        $_SESSION['notif'] = "Security Role successfully enlisted";
        $this->redirect(array('role/index', 'id'=>$id));
    }

}
