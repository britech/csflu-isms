<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigMeeting;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface WigMeetingDao {

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @return WigMeeting[]
     * @throws DataAccessException
     */
    public function listWigMeetings(UnitBreakthrough $unitBreakthrough);
    
    /**
     * @param String $id
     * @return WigMeeting
     * @throws DataAccessException
     */
    public function getWigMeetingData($id);
    
    /**
     * @param WigMeeting $wigMeeting
     * @param UnitBreakthrough $unitBreakthrough
     * @return String
     * @throws DataAccessException
     */
    public function insertWigMeeting(WigMeeting $wigMeeting, UnitBreakthrough $unitBreakthrough);
}
