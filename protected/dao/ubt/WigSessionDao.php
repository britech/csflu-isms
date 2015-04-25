<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface WigSessionDao {

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @return WigSession[]
     * @throws DataAccessException
     */
    public function listWigSessions(UnitBreakthrough $unitBreakthrough);
    
    /**
     * @param String $id
     * @return WigSession
     * @throws DataAccessException
     */
    public function getWigSessionData($id);
    
    /**
     * @param Commitment $commitment
     * @return WigSession
     * @throws DataAccessException
     */
    public function getWigSessionDataByCommitment(Commitment $commitment);
    
    /**
     * @param WigSession $wigSession
     * @param UnitBreakthrough $unitBreakthrough
     * @return String
     * @throws DataAccessException
     */
    public function insertWigSession(WigSession $wigSession, UnitBreakthrough $unitBreakthrough);
    
    /**
     * @param WigSession $wigSession
     * @throws DataAccessException
     */
    public function updateWigSession(WigSession $wigSession);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deleteWigSession($id);
}
