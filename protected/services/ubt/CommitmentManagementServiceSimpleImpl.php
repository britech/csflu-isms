<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\dao\ubt\CommitmentCrudDaoSqlImpl;
use org\csflu\isms\dao\ubt\WigSessionDaoSqlmpl;
use org\csflu\isms\service\ubt\CommitmentManagementService;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\ubt\Commitment;

/**
 * Description of CommitmentManagementServiceSimpleImpl
 *
 * @author britech
 */
class CommitmentManagementServiceSimpleImpl implements CommitmentManagementService {

    private $commitDaoSource;
    private $wigSessionDaoSource;
    private $logger;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->commitDaoSource = new CommitmentCrudDaoSqlImpl();
        $this->wigSessionDaoSource = new WigSessionDaoSqlmpl();
    }

    public function insertCommitments(WigSession $wigSession) {
        $commitments = $this->commitDaoSource->listCommitments($wigSession);
        $this->logger->debug("Number of commitments already enlisted: " . count($commitments));

        $commitmentsToEnlist = array();
        foreach ($wigSession->commitments as $commitment) {
            $found = false;
            foreach ($commitments as $data) {
                if ($commitment->user->id == $data->user->id && $commitment->commitment == $data->commitment) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $commitmentsToEnlist = array_merge($commitmentsToEnlist, array($commitment));
            }
        }
        $this->logger->debug("Number of commitments to be enlisted: " . count($commitmentsToEnlist));

        if (count($commitmentsToEnlist) == 0) {
            throw new ServiceException("No commitments enlisted");
        }

        $this->commitDaoSource->insertCommitments($wigSession);
    }

    public function listCommitments(UserAccount $userAccount, WigSession $wigSession) {
        $commitments = $this->commitDaoSource->listCommitments($wigSession);
        $commitmentsToDisplay = array();
        foreach ($commitments as $commitment) {
            if ($userAccount->id == $commitment->user->id) {
                $commitmentsToDisplay = array_merge($commitmentsToDisplay, array($commitment));
             }
        }
        return $commitmentsToDisplay;
    }

    public function getCommitmentData($id) {
        return $this->commitDaoSource->getCommitmentData($id);
    }

    public function updateCommitment(Commitment $commitment) {
        $wigSession = $this->wigSessionDaoSource->getWigSessionDataByCommitment($commitment);
        $commitments = $this->commitDaoSource->listCommitments($wigSession);
        foreach($commitments as $data){
            if(strcasecmp($commitment->commitment, $data->commitment) == 0 && $commitment->user->id == $data->user->id && $commitment->id != $data->id){
                throw new ServiceException("Commitment update not allowed");
            }
        }
        $this->commitDaoSource->updateCommitmentData($commitment);
    }

}
