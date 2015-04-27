<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\uam\Employee;

/**
 * Description of UserController
 *
 * @author britech
 */
class UserController extends Controller {

    private $userService;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->userService = new UserManagementService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - User Management';
        $this->render('user/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Account Maintenance' => 'active'),
            'sidebar' => array(
                'data' => $this->getSidebarData()),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function createAccount() {
        $this->title = ApplicationConstants::APP_NAME . ' - Create Account';

        $this->render('user/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Account Maintenance' => array('user/index'),
                'Initial Registration' => 'active'),
            'sidebar' => array('data' => $this->getSidebarData())));
    }

    private function getSidebarData() {
        return array(
            'header' => 'User Management',
            'links' => array(
                'Account Maintenance' => array('user/index'),
                'Security Role' => array('role/index')
        ));
    }

    public function listEmployees() {
        $data = $this->userService->listEmployees();

        $employees = array();
        foreach ($data as $employee) {
            if ($employee->id != $_SESSION['employee']) {
                $status = $employee->loginAccount->status == 1 ? "Active" : ($employee->loginAccount->status == 0 ? "Inactive" : "Unknown");
                array_push($employees, array(
                    'name' => $employee->lastName . ', ' . $employee->givenName,
                    'status' => $status,
                    'action' => ApplicationUtils::generateLink(array('user/manageAccount', 'id' => $employee->id), 'Manage Account')));
            }
        }
        $this->renderAjaxJsonResponse($employees);
    }

    public function validateEmployee() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $result = $this->userService->validateEmployee($id);
        $enlistedEmployee = $this->userService->getEmployeeData($id);

        if (!is_null($result->id)) {
            if ($enlistedEmployee->id === $result->id) {
                $respCode = '11';
                $this->renderAjaxJsonResponse(array('respCode' => $respCode, 'respMessage' => 'Employee is already listed in the system. Please enter another employee ID to continue.'));
            } else {
                $respCode = '00';
                $this->renderAjaxJsonResponse(array('respCode' => $respCode, 'id' => $result->id,
                    'givenName' => $result->givenName,
                    'lastName' => $result->lastName,
                    'middleName' => $result->middleName,
                    'deptCode' => $result->department->code));
            }
        } else {
            $respCode = '10';
            $this->renderAjaxJsonResponse(array('respCode' => $respCode, 'respMessage' => 'Employee not found. Please enter another employee ID to continue.'));
        }
    }

    public function insertAccount() {
        $employeeData = filter_input_array(INPUT_POST)['Employee'];
        $departmentData = filter_input_array(INPUT_POST)['Department'];
        $positionData = filter_input_array(INPUT_POST)['Position'];
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];
        $accountData = filter_input_array(INPUT_POST)['LoginAccount'];

        $account = new UserAccount();
        $account->bindValuesUsingArray(array(
            'useraccount' => null,
            'employee' => $employeeData,
            'department' => $departmentData,
            'position' => $positionData,
            'securityrole' => $securityRoleData,
            'loginaccount' => $accountData));

        print_r($account);
        $this->userService->createAccount($account);
        $this->redirect(array('user/manageAccount', 'id' => $account->employee->id));
    }

    public function manageAccount($id) {
        $employee = $this->userService->getEmployeeData($id);
        if ($employee->id == $this->getSessionData('employee')) {
            $this->setSessionData('notif', array('message' => "You are not allowed to manage your own account"));
            $this->redirect(array('user/index'));
        } else {
            if (is_null($employee->id)) {
                $this->setSessionData('notif', array('message' => "Account not found"));
                $this->redirect(array('user/index'));
            }
            $status = $this->userService->getLoginAccountStatus($id);
            $this->title = ApplicationConstants::APP_NAME . ' - Manage Account';
            $this->render('user/manage', array(
                'breadcrumb' => array(
                    'Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'employee' => $id,
                'name' => $employee->givenName . ' ' . $employee->lastName,
                'username' => $employee->loginAccount->username,
                'status' => $status,
                'notif' => $this->getSessionData('notif')
            ));
            $this->unsetSessionData('notif');
        }
    }

    public function renderAccountGrid() {
        $id = filter_input(INPUT_POST, 'id');
        $employee = new Employee();
        $employee->id = $id;

        if (isset($id) && !empty($id)) {
            $accounts = $this->userService->listAccounts($employee);

            $accountsArray = array();
            foreach ($accounts as $account) {
                array_push($accountsArray, array('role' => $account->securityRole->description,
                    'department' => $account->employee->department->name,
                    'position' => $account->employee->position->name,
                    'link' => ApplicationUtils::generateLink(array('user/updateLink', 'id' => $account->id), 'Update Link')
                    . '&nbsp;|&nbsp;' .
                    ApplicationUtils::generateLink(array('user/confirmDeleteLinkedRole', 'id' => $account->id), 'Unlink Role')));
            }
            $this->renderAjaxJsonResponse($accountsArray);
        } else {
            throw new ControllerException('Data is needed to process request');
        }
    }

    public function confirmResetPassword() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Account';
        $id = filter_input(INPUT_GET, 'id');
        if (isset($id) && !empty($id)) {
            $employee = $this->userService->getEmployeeData($id);
            if (is_null($employee->id)) {
                $_SESSION['notif'] = "Account not found";
                $this->redirect(array('user/index'));
            }
            $status = $this->userService->getLoginAccountStatus($id);
            $this->render('commons/confirm', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => array('user/manageAccount', 'id' => $id),
                    'Confirm Password Reset' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'employee' => $id,
                'username' => $employee->loginAccount->username,
                'name' => $employee->givenName . ' ' . $employee->lastName,
                'status' => $status,
                'confirm' => array('class' => 'info',
                    'header' => 'Confirm password reset',
                    'text' => 'Do you to reset the password of this account? Continuing on this action will replace the password with the account username.',
                    'accept.class' => 'green',
                    'accept.text' => 'Continue',
                    'accept.url' => array('user/resetPassword', 'id' => $id),
                    'deny.class' => '',
                    'deny.text' => 'Back',
                    'deny.url' => array('user/manageAccount', 'id' => $id))));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function resetPassword() {
        $id = filter_input(INPUT_GET, 'id');
        if (isset($id) && !empty($id)) {
            $this->userService->resetPassword($id);
            $_SESSION['notif'] = array('class' => 'info', 'message' => "Password reset successfull. Default password is the account username");
            $this->redirect(array('user/manageAccount', 'id' => $id));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function confirmStatusToggle() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Account';
        $id = filter_input(INPUT_GET, 'id');
        $stat = filter_input(INPUT_GET, 'stat');

        $condition = (isset($id) && !empty($id)) && (isset($stat));
        if ($condition) {
            $employee = $this->userService->getEmployeeData($id);
            if (is_null($employee->id)) {
                $_SESSION['notif'] = "Account not found";
                $this->redirect(array('user/index'));
            }
            $status = $this->userService->getLoginAccountStatus($id);
            $statusName = $stat == 1 ? "Active" : "Inactive";
            $this->render('commons/confirm', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => array('user/manageAccount', 'id' => $id),
                    'Confirm Status Update' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'employee' => $id,
                'username' => $employee->loginAccount->username,
                'name' => $employee->givenName . ' ' . $employee->lastName,
                'status' => $status,
                'confirm' => array('class' => 'info',
                    'header' => 'Confirm update of login account status',
                    'text' => "Do you want to update the login account status? Continuing this action will set the account status to {$statusName}",
                    'accept.class' => 'black',
                    'accept.text' => 'Continue',
                    'accept.url' => array('user/toggleAccountStatus', 'id' => $id, 'stat' => $stat),
                    'deny.class' => '',
                    'deny.text' => 'Back',
                    'deny.url' => array('user/manageAccount', 'id' => $id))));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function toggleAccountStatus() {
        $id = filter_input(INPUT_GET, 'id');
        $status = filter_input(INPUT_GET, 'stat');

        $condition = (isset($id) && !empty($id)) && (isset($status));
        if ($condition) {
            $this->userService->updateLoginAccountStatus($id, $status);
            $statusName = $status == 1 ? "Active" : "Inactive";
            $_SESSION['notif'] = array('class' => 'info', 'message' => "Account is now {$statusName}");
            $this->redirect(array('user/manageAccount', 'id' => $id));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function confirmDeleteLinkedRole() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Account';
        $id = filter_input(INPUT_GET, 'id');

        if (isset($id) && !empty($id)) {
            $account = $this->userService->getAccountById($id);
            if (is_null($account->employee->id)) {
                $_SESSION['notif'] = "Account not found";
                $this->redirect(array('user/index'));
            }
            $this->render('commons/confirm', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => array('user/manageAccount', 'id' => $account->employee->id),
                    'Unlink Security Role' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'employee' => $account->employee->id,
                'username' => $account->employee->loginAccount->username,
                'name' => $account->employee->givenName . ' ' . $account->employee->lastName,
                'status' => $account->employee->loginAccount->status,
                'confirm' => array('class' => 'info',
                    'header' => 'Delete Linked Security Role',
                    'text' => "Do you want to remove the linked security role? Choosing 'Yes' will delete the link from this account.",
                    'accept.class' => 'red',
                    'accept.text' => 'Yes',
                    'accept.url' => array('user/deleteLink', 'id' => $id),
                    'deny.class' => 'green',
                    'deny.text' => 'No',
                    'deny.url' => array('user/manageAccount', 'id' => $account->employee->id))));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function deleteLink() {
        $id = filter_input(INPUT_GET, 'id');
        if (isset($id) && !empty($id)) {
            $account = $this->userService->getAccountById($id);
            $this->userService->unlinkSecurityRole($id);
            $_SESSION['notif'] = array('class' => 'error', 'message' => "<b>{$account->securityRole->description}</b> designated at <b>{$account->employee->department->name}</b> was unlinked to this account.");
            $this->redirect(array('user/manageAccount', 'id' => $account->employee->id));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function viewChangePasswordForm() {
        if (isset($_SESSION['employee']) && !is_null($_SESSION['employee'])) {
            $this->title = ApplicationConstants::APP_NAME . ' - Change Password';
            $this->layout = "column-1";
            $this->render('user/changePassword', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Change Password' => 'active'),
                'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
            ));

            if (isset($_SESSION['notif'])) {
                unset($_SESSION['notif']);
            }
        } else {
            $this->redirect(array('site/login'));
        }
    }

    public function checkPassword() {
        $password = filter_input(INPUT_POST, 'password');
        if (isset($password) && !empty($password)) {
            $currentPassword = $this->userService->getSecurityKey($_SESSION['employee']);
            if ($currentPassword == $password) {
                $responseCode = '00';
                $responseMessage = "";
            } else {
                $responseCode = '20';
                $responseMessage = "Invalid passsword. Please try again";
            }
            $this->renderAjaxJsonResponse(array('respCode' => $responseCode, 'respMessage' => $responseMessage));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function changePassword() {
        $input = filter_input_array(INPUT_POST)['LoginAccount'];
        if (isset($input)) {
            $employee = new Employee();
            $employee->bindValuesUsingArray(array('loginaccount' => $input, 'employee' => array('id' => $_SESSION['employee'])));
            $this->userService->updateSecurityKey($employee);
            $_SESSION['notif'] = "Password successfully updated";
            $this->redirect(array('user/viewChangePasswordForm'));
        } else {
            $_SESSION['notif'] = "Please fill the form properly";
            $this->redirect(array('user/viewChangePasswordForm'));
        }
    }

    public function linkForm() {
        $id = filter_input(INPUT_GET, 'id');
        if (isset($id) && !empty($id)) {
            $this->title = ApplicationConstants::APP_NAME . ' - Link Security Role';
            $employee = $this->userService->getEmployeeData($id);
            if (is_null($employee->id)) {
                $_SESSION['notif'] = "Account not found";
                $this->redirect(array('user/index'));
            }

            $this->render('user/linkRole', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => array('user/manageAccount', 'id' => $id),
                    'Link a Security Role' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'employee' => $id,
                'username' => $employee->loginAccount->username,
                'name' => $employee->givenName . ' ' . $employee->lastName,
                'status' => $employee->loginAccount->status
            ));
        } else {
            throw new ControllerException('Another parameter is needed to process this request');
        }
    }

    public function linkRole() {
        $employeeData = filter_input_array(INPUT_POST)['Employee'];
        $departmentData = filter_input_array(INPUT_POST)['Department'];
        $positionData = filter_input_array(INPUT_POST)['Position'];
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];

        $condition = isset($employeeData) && isset($departmentData) && isset($positionData) && isset($securityRoleData);
        if ($condition) {
            $account = new UserAccount();
            $account->bindValuesUsingArray(array('employee' => $employeeData,
                'department' => $departmentData,
                'position' => $positionData,
                'securityrole' => $securityRoleData,
                'useraccount' => array()));
            $this->userService->linkSecurityRole($account);
            $_SESSION['notif'] = array('class' => 'info', 'message' => "Security role successfully linked to this account");
            $this->redirect(array('user/manageAccount', 'id' => $employeeData['id']));
        } else {
            throw new ControllerException('Form data is needed to process this request');
        }
    }

    public function updateLink() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $account = $this->userService->getAccountById($id);
        if (is_null($account->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Linked security role not found');
            $this->redirect(array('user/index'));
        } else {
            $this->title = ApplicationConstants::APP_NAME . ' - Update Security Role';
            $this->render('user/updateLink', array(
                'breadcrumb' => array('Home' => array('site/index'),
                    'Account Maintenance' => array('user/index'),
                    'Manage Account' => array('user/manageAccount', 'id' => $account->employee->id),
                    'Link a Security Role' => 'active'),
                'sidebar' => array('file' => 'user/_profile'),
                'account' => $account->id,
                'employee' => $account->employee->id,
                'username' => $account->employee->loginAccount->username,
                'name' => $account->employee->givenName . ' ' . $account->employee->lastName,
                'status' => $account->employee->loginAccount->status,
                'role' => $account->securityRole->id,
                'position' => $account->employee->position->id
            ));
        }
    }

    public function linkUpdate() {
        $securityRoleData = filter_input_array(INPUT_POST)['SecurityRole'];
        $positionData = filter_input_array(INPUT_POST)['Position'];
        $employeeData = filter_input_array(INPUT_POST)['Employee'];
        $accountData = filter_input_array(INPUT_POST)['UserAccount'];


        $condition = !isset($securityRoleData) || !isset($positionData) || !isset($employeeData) || !isset($accountData);

        if ($condition) {
            throw new ControllerException('Form data is expected to process this request');
        }

        $account = new UserAccount();
        $account->bindValuesUsingArray(array(
            'securityrole' => $securityRoleData,
            'position' => $positionData,
            'employee' => $employeeData,
            'useraccount' => $accountData
        ));

        $this->userService->updateAccount($account);

        $_SESSION['notif'] = array('class' => 'info', 'message' => "Link update successfull");
        $this->redirect(array('user/manageAccount', 'id' => $account->employee->id));
    }

}
