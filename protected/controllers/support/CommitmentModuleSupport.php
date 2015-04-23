<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;
use org\csflu\isms\service\ubt\CommitmentManagementServiceSimpleImpl;

/**
 * Description of CommitmentModuleSupport
 *
 * @author britech
 */
class CommitmentModuleSupport {

    private static $instance = null;
    private $ubtService;
    private $userService;
    private $commitmentService;
    private $logger;
    private $controller;

    private function __construct(Controller $controller) {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl();
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->commitmentService = new CommitmentManagementServiceSimpleImpl();
        $this->controller = $controller;
    }

    /**
     * Returns the singleton instance of the module support class
     * @param Controller $controller Used to access the session variable handling and redirection mechanism
     * @return CommitmentModuleSupport
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new CommitmentModuleSupport($controller);
        }
        return self::$instance;
    }

    /**
     * Loads an open WIG Session by a given department
     * @param Department $department
     * @param boolean $remote Optional. If the request is done via AJAX 
     * @return WigSession
     */
    public function loadOpenWigSession(Department $department, $remote = false) {
        $unitBreakthroughs = $this->ubtService->listUnitBreakthrough(null, $department, true);
        if (count($unitBreakthroughs) == 0) {
            $url = array('ubt/manage');
            $this->controller->setSessionData('notif', array('message' => 'No active UnitBreakthroughs defined'));
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }

        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($unitBreakthroughs[0]->id);
        foreach ($unitBreakthrough->wigMeetings as $wigSession) {
            if ($wigSession->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN) {
                return $wigSession;
            }
        }

        $this->controller->setSessionData('notif', array('class' => 'error', 'message' => "No open WigSession found for {$unitBreakthrough->description}"));
        if ($remote) {
            $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
        } else {
            $this->controller->redirect($url);
        }
    }

    /**
     * Loads the account model through an attached variable
     * @param boolean $remote Optional. If the request is done via AJAX
     * @return UserAccount
     */
    public function loadAccountModel($remote = false) {
        $account = $this->userService->getAccountById($this->controller->getSessionData('user'));
        if (is_null($account->id)) {
            $url = array('site/logout');
            $this->logger->warn("User {$this->controller->getSessionData('user')} was not found. Forcing log-out mechanism");
            if ($remote) {
                $this->controller->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->controller->redirect($url);
            }
        }
        return $account;
    }

    /**
     * Lists the commitments assigned to the given UserAccount entity
     * @param UserAccount $userAccount
     * @return Commitment[]
     */
    public function listCommitments(UserAccount $userAccount) {
        $wigSession = $this->loadOpenWigSession($userAccount->employee->department);
        return $this->commitmentService->listCommitments($userAccount, $wigSession);
    }

}
