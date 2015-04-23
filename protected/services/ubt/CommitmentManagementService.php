<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\exceptions\ServiceException;

/**
 *
 * @author britech
 */
interface CommitmentManagementService {
    
    /**
     * Inserts the commitments through a WigSession entity
     * @param WigSession $wigSession
     * @return Commitment[]
     * @throws ServiceException
     */
    public function insertCommitments(WigSession $wigSession);
    
    /**
     * Lists the commitments on a given UserAccount and WigSession entities
     * @param UserAccount $userAccount
     * @param WigSession $wigSession
     * @return Commitment[]
     */
    public function listCommitments(UserAccount $userAccount, WigSession $wigSession);
}
