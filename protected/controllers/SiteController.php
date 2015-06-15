<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\models\uam\LoginAccount;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;

class SiteController extends Controller {

    private $userService;

    public function __construct() {
        $this->layout = "simple";
        $this->userService = new UserManagementService();
    }

    public function index() {
        if (empty($this->getSessionData('user'))) {
            $this->redirect(array('site/login'));
        } else {
            $this->title = ApplicationConstants::APP_NAME . ' - Welcome';
            $this->layout = 'column-1';
            $this->render('site/index', array('breadcrumb' => array('Home' => 'active')));
        }
    }

    public function login() {
        $this->title = ApplicationConstants::APP_NAME . ' - Login';

        if (!empty($_SESSION['user'])) {
            $this->redirect(array('site/index'));
        } else {
            $this->render('site/login', array(
                'model' => new LoginAccount(),
                'notif' => $this->getSessionData('notif')
            ));
            $this->unsetSessionData('notif');
        }
    }

    public function authenticate() {
        $this->validatePostData(array('LoginAccount'));

        $loginAccountData = $this->getFormData('LoginAccount');
        $loginAccount = new LoginAccount();
        $loginAccount->bindValuesUsingArray(array('loginaccount' => $loginAccountData), $loginAccount);

        if ($loginAccount->validate()) {
            $employee = $this->userService->authenticate($loginAccount);

            if (!is_null($employee->id)) {
                $this->setSessionData('employee', $employee->id);
                $this->redirect(array('site/loadAccounts', 'employee' => $employee->id));
            } else {
                $this->setSessionData('notif', array('class' => 'error', 'message' => 'Access Denied'));
                $this->redirect(array('site/login'));
            }
        } else {
            $this->setSessionData('notif', array('message' => 'Username and password are required fields'));
            $this->redirect(array('site/login'));
        }
    }

    public function selectAccount() {
        $this->validatePostData(array('userId'));
        $id = $this->getFormData('userId');

        if (!empty($id)) {
            $this->setSessionData('user', $id);
            $this->redirect(array('site/index'));
        } else {
            $this->redirect(array('site/logout'));
        }
    }

    public function loadAccounts($employee) {
        $sessionEmployeeId = $this->getSessionData('employee');
        if (!empty($sessionEmployeeId) && !empty($sessionEmployeeId)) {
            if ($sessionEmployeeId != $employee) {
                $this->redirect(array('site/logout'));
            } else {
                $employeeModel = new Employee();
                $employeeModel->id = $sessionEmployeeId;
                $accounts = $this->userService->listAccounts($employeeModel);
                $this->resolveEntry($accounts);
            }
        } else {
            $this->setSessionData('notif', array('message' => 'Please enter your account credentials to continue'));
            $this->redirect(array('site/login'));
        }
    }

    private function resolveEntry($accounts) {
        if (count($accounts) == 1) {
            $_SESSION['user'] = $accounts[0]->id;
            $this->redirect(array('site/index'));
        } else {
            $this->title = ApplicationConstants::APP_NAME . ' - Account Selection';
            $this->render('site/accounts', array('accounts' => ApplicationUtils::generateListData($accounts, 'id', 'getSecurityRoleDepartmentDisplay')));
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect(array('site/login'));
    }

}
