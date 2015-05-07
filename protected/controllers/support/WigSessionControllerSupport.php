<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\reports\IpReportOutput;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
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
    private $modelLoaderUtil;

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($controller);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($controller);
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

    /**
     * Resolves the movement data in the LeadMeasure component
     * @param WigSession $wigSession
     * @param LeadMeasure[] $leadMeasures
     * @param UnitBreakthroughMovement $movementData
     * @param int $designation
     * @return string
     */
    public function resolveLeadMeasureMovements(WigSession $wigSession, array $leadMeasures, UnitBreakthroughMovement $movementData, $designation) {
        foreach ($leadMeasures as $leadMeasure) {
            if ($wigSession->startingPeriod >= $leadMeasure->startingPeriod && $wigSession->endingPeriod <= $leadMeasure->endingPeriod && $designation == $leadMeasure->designation) {
                return $this->retrieveLeadMeasureMovement($leadMeasure, $movementData, $designation);
            }
        }
    }

    private function retrieveLeadMeasureMovement(LeadMeasure $leadMeasure, UnitBreakthroughMovement $movementData, $designation) {
        if ($designation == LeadMeasure::DESIGNATION_1 && $leadMeasure->designation == LeadMeasure::DESIGNATION_1) {
            return $this->resolveLeadMeasureMovement($movementData->firstLeadMeasureFigure, $leadMeasure);
        } elseif ($designation == LeadMeasure::DESIGNATION_2 && $leadMeasure->designation == LeadMeasure::DESIGNATION_2) {
            return $this->resolveLeadMeasureMovement($movementData->secondLeadMeasureFigure, $leadMeasure);
        }
    }

    /**
     * Resolves the movement data in the UnitBreakthrough component
     * @param UnitBreakthroughMovement $movementData
     * @param UnitBreakthrough $ubt
     * @return string
     */
    public function resolveUnitBreakthroughMovement(UnitBreakthroughMovement $movementData, UnitBreakthrough $ubt) {
        if (strlen($movementData->ubtFigure) < 1) {
            return "No Movement";
        } else {
            return "{$movementData->ubtFigure} {$ubt->uom->getAppropriateUomDisplay()}";
        }
    }

    private function resolveLeadMeasureMovement($leadMeasureMovement, LeadMeasure $leadMeasure) {
        if(strlen($leadMeasureMovement) < 1){
            return "No Movement";
        } else {
            return "{$leadMeasureMovement} {$leadMeasure->uom->getAppropriateUomDisplay()}";
        }
    }
    
    /**
     * Retrieves the aligned Lead Measure entity from the time the WigSession is established
     * @param WigSession $wigSession
     * @param LeadMeasure[] $leadMeasures
     * @param int $designation
     * @return LeadMeasure
     */
    public function retrieveAlignedLeadMeasure(WigSession $wigSession, array $leadMeasures, $designation){
        $wigSessionData = $this->modelLoaderUtil->loadWigSessionModel($wigSession->id);
        foreach($leadMeasures as $leadMeasure){
            if($wigSessionData->startingPeriod >= $leadMeasure->startingPeriod && $wigSessionData->endingPeriod <= $leadMeasure->endingPeriod && $designation == $leadMeasure->designation){
                return $leadMeasure;
            }
        }
    }

}
