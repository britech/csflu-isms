<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;
use org\csflu\isms\exceptions\ControllerException;
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
        $this->isRbacEnabled = true;
        $this->moduleCode = ModuleAction::MODULE_SYS;
        $this->actionCode = "MS";
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
                'action' => ApplicationUtils::generateLink(array('role/updateRole', 'id' => $role->id), 'Update Details').'&nbsp;|&nbsp;'.ApplicationUtils::generateLink(array('role/removeRole', 'id'=>$role->id), 'Remove')));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function updateRole() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
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
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $securityRole = new SecurityRole();
        $securityRole->bindValuesUsingArray(array('securityrole' => $securityRoleData, 'moduleactions' => $allowableActionRoleData));

        $this->userService->updateSecurityRole($securityRole);
        $_SESSION['notif'] = "Security Role successfully updated";
        $this->redirect(array('role/updateRole', 'id' => $securityRole->id));
    }

    public function createRole() {
        $this->title = ApplicationConstants::APP_NAME . ' - Add Security Role';
        $this->render('user/createRole', array(
            'breadcrumb' => array('Home' => array('site/index'),
                'Security Roles' => array('role/index'),
                'Add Security Role' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData()),
            'model' => new ModuleAction()
        ));
    }

    public function create() {
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];
        $allowableActionRoleData = filter_input_array(INPUT_POST)['AllowableAction'];

        $condition = (isset($securityRoleData) && !empty($securityRoleData)) || (isset($allowableActionRoleData) && !empty($allowableActionRoleData));

        if (!$condition) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $securityRole = new SecurityRole();
        $securityRole->bindValuesUsingArray(array('securityrole' => $securityRoleData, 'moduleactions' => $allowableActionRoleData));
        $id = $this->userService->enlistSecurityRole($securityRole);
        $_SESSION['notif'] = "Security Role successfully enlisted";
        $this->redirect(array('role/index', 'id' => $id));
    }

    public function removeRole() {
        $id = filter_input(INPUT_GET, 'id');
        
        $this->title = ApplicationConstants::APP_NAME . ' - Security Role';
        if(!isset($id) && empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        
        $securityRole = $this->userService->getSecurityRoleData($id);
        
        $this->render('commons/confirm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Security Roles' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData()),
            'confirm' => array('class' => '',
                    'header' => 'Confirm removal of Security Role',
                    'text' => "Do you want to remove the security role <strong>{$securityRole->description}</strong>? Continuing this action will delete the linked accounts using this security role.",
                    'accept.class' => 'red',
                    'accept.text' => 'Yes',
                    'accept.url' => array('role/remove', 'id' => $id),
                    'deny.class' => 'green',
                    'deny.text' => 'No',
                    'deny.url' => array('role/index'))
        ));
    }

    public function remove() {
        $id = filter_input(INPUT_GET, 'id');
        if(!isset($id) && empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $securityRole = new SecurityRole();
        $securityRole->id = $id;
        $this->userService->removeSecurityRole($securityRole);
        $_SESSION['notif'] = "Security Role successfully deleted";
        $this->redirect(array('role/index', 'id' => $id));
    }
    
    public function getSecurityRole() {
        $id = filter_input(INPUT_POST, 'id');
        
        if(!isset($id) && empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $securityRole = $this->userService->getSecurityRoleData($id);
        $this->renderPartial('user/_roleDetail', array('actions' => $securityRole->allowableActions));
    }
    
    public function listSecurityRoles(){
        $roles = $this->userService->listSecurityRoles();
        $data = array();
        foreach($roles as $role) {
            array_push($data, array('id'=>$role->id, 'name'=>'&nbsp;'.$role->description));
        }
        $this->renderAjaxJsonResponse($data);
    }
}
