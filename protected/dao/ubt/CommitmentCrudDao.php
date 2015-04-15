<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface CommitmentCrudDao {

    /**
     * @param WigSession $wigSession
     * @return Commitment[]
     * @throws DataAccessException
     */
    public function listCommitments(WigSession $wigSession);
    
    /**
     * @param String $id
     * @return Commitment
     * @throws DataAccessException
     */
    public function getCommitmentData($id);
    
    /**
     * @param WigSession $wigSession
     * @throws DataAccessException
     */
    public function insertCommitments(WigSession $wigSession);
}
