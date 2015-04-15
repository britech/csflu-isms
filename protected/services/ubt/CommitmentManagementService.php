<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;
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
}
