<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\reports\IpReportOutput;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;

/**
 * Description of WigSessionControllerSupport
 *
 * @author britech
 */
class WigSessionControllerSupport {

    private static $instance = null;
    private $logger;
    private $controller;
    private $commitmentModuleSupport;
    private $userService;

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($controller);
        $this->userService = new SimpleUserManagementServiceImpl();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    /**
     * Returns the singleton instance of the module support class
     * @param Controller $controller Used to access the session variable handling and redirection mechanism
     * @return WigSessionControllerSupport
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new WigSessionControllerSupport($controller);
        }
        return self::$instance;
    }

    /**
     * Returns the list of employees based on the department
     * @return UserAccount[]
     */
    public function listEmployees() {
        $account = $this->commitmentModuleSupport->loadAccountModel();
        return $this->userService->listAccounts(null, $account->employee->department);
    }

    /**
     * Returns the summarized commitments by 
     * @param WigSession $wigSession
     * @return array
     */
    public function collateCommitments(WigSession $wigSession) {
        $accountIds = array();
        $ipReportOutputs = array();
        $accounts = $this->listEmployees();
        foreach ($accounts as $account) {
            $filteredCommitments = $this->filterCommitments($wigSession->commitments, $account);
            $accountIds = array_merge($accountIds, array($account->id));
            $ipReportOutputs = array_merge($ipReportOutputs, array(new IpReportOutput($filteredCommitments)));
        }
        $output = array_combine($accountIds, $ipReportOutputs);
        return $output;
    }

    /**
     * 
     * @param Commitment[] $commitments
     * @param UserAccount $userAccount
     */
    private function filterCommitments(array $commitments, UserAccount $userAccount) {
        $output = array();
        foreach ($commitments as $commitment) {
            if ($userAccount->id == $commitment->user->id) {
                $output = array_merge($output, array($commitment));
            }
        }
        return $output;
    }

}
