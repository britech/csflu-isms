<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthrough;
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
     * @param WigSession $wigSession
     * @param UnitBreakthrough $unitBreakthrough
     * @return String
     * @throws DataAccessException
     */
    public function insertWigSession(WigSession $wigSession, UnitBreakthrough $unitBreakthrough);
    
    public function updateWigSession(WigSession $wigSession);
}
