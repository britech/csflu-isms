<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller as Controller;
use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;
use org\csflu\isms\models\uam\Login as Login;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;

class SiteController extends Controller {

    private $userService;

    public function __construct() {
        $this->layout = "simple";
        $this->userService = new UserManagementService();
    }

    public function index() {
        if (!empty($_SESSION['user'])) {
            $this->title = ApplicationConstants::APP_NAME . ' - Welcome';
            $this->layout = 'main';
            $this->render('site/index');
        } else {
            $this->redirect(array('site/login'));
        }
    }

    public function login() {
        $this->title = ApplicationConstants::APP_NAME . ' - Login';

        if (!empty($_SESSION['user'])) {
            $this->redirect(array('site/index'));
        } else {
            if (isset($_SESSION['login.notif'])) {
                $this->render('site/login', array('login.notif' => $_SESSION['login.notif']));
                unset($_SESSION['login.notif']);
            } else {
                $this->render('site/login');
            }
        }
    }

    public function authenticate() {
        $formValues = filter_input_array(INPUT_POST)['Login'];

        $login = new Login();
        $login->bindValuesUsingArray($formValues);

        if ($login->validate()) {
            $employee = $this->userService->authenticate($login);

            if (!empty($employee->id)) {
                $_SESSION['employee'] = $employee->id;
                $this->redirect(array('site/loadAccounts', 'employee' => $employee->id));
            } else {
                $_SESSION['login.notif'] = "Access Denied";
                $this->redirect(array('site/login'));
            }
        } else {
            $_SESSION['login.notif'] = "Username and password are required fields";
            $this->redirect(array('site/login'));
        }
    }

    public function selectAccount() {
        $id = filter_input(INPUT_POST, 'userId');

        if (!empty($id)) {
            $_SESSION['user'] = $id;
            $this->redirect(array('site/index'));
        } else {
            $this->redirect(array('site/logout'));
        }
    }

    public function loadAccounts() {
        $sessionEmployeeId = $_SESSION['employee'];
        $requestEmployeeId = filter_input(INPUT_GET, 'employee');
        if (!empty($sessionEmployeeId) && !empty($sessionEmployeeId)) {
            if ($sessionEmployeeId != $requestEmployeeId) {
                unset($_SESSION['employee']);
                unset($_SESSION['user']);
                $_SESSION['login.notif'] = "Please enter your account credentials to continue";
                $this->redirect(array('site/login'));
            } else {
                $this->title = ApplicationConstants::APP_NAME . ' - Account Selection';
                $accounts = $this->userService->listAccounts($sessionEmployeeId);
                $this->resolveEntry($accounts);
            }
        } else {
            $_SESSION['login.notif'] = "Please enter your account credentials to continue";
            $this->redirect(array('site/login'));
        }
    }

    private function resolveEntry($accounts) {
        if (count($accounts) == 1) {
            $_SESSION['user'] = $accounts[0]->id;
            $this->redirect(array('site/index'));
        } else {
            $itemIds = array();
            $itemValues = array();
            foreach ($accounts as $account) {
                array_push($itemIds, $account->id);
                array_push($itemValues, $account->securityRole->name . '&nbsp;-&nbsp;' . $account->employee->department->name);
            }
            $this->render('site/accounts', array('accounts' => array_combine($itemIds, $itemValues)));
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect(array('site/login'));
    }

}
