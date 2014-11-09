<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\core\Model;
/**
 *
 * @author britech
 */
interface RevisionHistoryLoggingService {
    
    /**
     * Retrieves the revision history of the selected reference ID
     * @param String $moduleId
     * @return RevisionHistory[]
     * @throws ServiceException
     */
    public function getRevisionHistoryList($moduleId);
    
    /**
     * Logs the changes
     * @param RevisionHistory $revisionHistory
     * @param Model $model
     * @throws ServiceException
     */
    public function log($revisionHistory, $model);
}
