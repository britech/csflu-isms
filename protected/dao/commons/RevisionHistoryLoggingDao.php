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
     * @param string $moduleCode
     * @param string $referenceId
     * @return RevisionHistory[]
     * @throws DataAccessException
     */
    public function getRevisionHistoryList($moduleCode, $referenceId);
    
    /**
     * @param RevisionHistory $revisionHistory
     * @throws DataAccessException
     */
    public function log(RevisionHistory $revisionHistory);
}
