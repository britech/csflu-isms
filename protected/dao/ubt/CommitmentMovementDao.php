<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\CommitmentMovement;

/**
 *
 * @author britech
 */
interface CommitmentMovementDao {

    /**
     * @param Commitment $commitment
     * @return CommitmentMovement[]
     * @throws DataAccessException
     */
    public function listMovements(Commitment $commitment);
}
