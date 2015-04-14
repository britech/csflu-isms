<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;

/**
 * Description of IpController
 *
 * @author britech
 */
class IpController extends Controller {

    private $userService;

    public function __construct() {
        $this->checkAuthorization();
        $this->userService = new UserManagementService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Performance Socrecard';
        $this->layout = "column-2";
        $this->render('ip/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => 'active'
            ),
            'sidebar' => array(
                'file' => 'ip/_profile'
            ),
            'account' => $this->loadAccountModel(),
            'commitments' => array()
        ));
    }

    private function loadAccountModel($remote = false) {
        $account = $this->userService->getAccountById($this->getSessionData('user'));
        if (is_null($account->id)) {
            $url = array('site/logout');
            $this->setSessionData('login.notif', "Please enter your credentials to continue");
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        return $account;
    }

}
