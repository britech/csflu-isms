<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\dao\ubt\CommitmentCrudDaoSqlImpl;
use org\csflu\isms\service\ubt\CommitmentManagementService;
use org\csflu\isms\models\ubt\WigSession;

/**
 * Description of CommitmentManagementServiceSimpleImpl
 *
 * @author britech
 */
class CommitmentManagementServiceSimpleImpl implements CommitmentManagementService {

    private $commitDaoSource;
    private $logger;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->commitDaoSource = new CommitmentCrudDaoSqlImpl();
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
    }

}
