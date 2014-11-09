<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface RevisionHistoryLoggingDao {
    
    /**
     * @param String $moduleId
     * @return RevisionHistory[]
     * @throws DataAccessException
     */
    public function getRevisionHistoryList($moduleId);
    
    /**
     * @param RevisionHistory $revisionHistory
     * @throws DataAccessException
     */
    public function log($revisionHistory);
}
