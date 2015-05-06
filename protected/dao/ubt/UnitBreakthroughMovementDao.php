<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\WigMeeting;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface UnitBreakthroughMovementDao {
    
    /**
     * @param WigSession $wigSession
     * @throws DataAccessException
     */
    public function closeWigSession(WigSession $wigSession);
    
    /**
     * @param WigSession $wigSession
     * @throws DataAccessException
     */
    public function recordUbtMovement(WigSession $wigSession);
    
    /**
     * @param WigSession $wigSession
     * @return WigMeeting
     * @throws DataAccessException
     */
    public function retrieveWigMeetingData(WigSession $wigSession);
    
    /**
     * @param WigSession $wigSession
     * @return UnitBreakthroughMovement[]
     * @throws DataAccessException
     */
    public function listUnitBreakthroughMovements(WigSession $wigSession);
}
