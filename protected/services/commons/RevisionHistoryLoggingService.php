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
     * Logs initial registrations or Add actions
     * @param RevisionHistory $revisionHistory
     * @param Model $model
     * @throws ServiceException
     */
    public function logNewAction($revisionHistory, $model);
    
    /**
     * Logs changes of an entity
     * @param RevisionHistory $revisionHistory
     * @param Model $model
     * @param Model $oldModel
     */
    public function logUpdateAction($revisionHistory, $model, $oldModel);
    
    /**
     * Logs deleted entries of subcomponents of a Core Entity
     */
    public function logDeleteAction($revisionHistory, $model);
}
