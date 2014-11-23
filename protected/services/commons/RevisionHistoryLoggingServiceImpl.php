<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\service\commons\RevisionHistoryLoggingService;
use org\csflu\isms\dao\commons\RevisionHistoryLoggingDaoSqlImpl as RevisionHistoryLoggingDao;

/**
 * Description of RevisionHistoryLoggingServiceImpl
 *
 * @author britech
 */
class RevisionHistoryLoggingServiceImpl implements RevisionHistoryLoggingService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new RevisionHistoryLoggingDao();
    }

    public function getRevisionHistoryList($moduleId) {
        return $this->daoSource->getRevisionHistoryList($moduleId);
    }

    public function logNewAction($revisionHistory, $model) {
        $revisionHistory->notes = $model->getModelTranslationAsNewEntity();
        $this->daoSource->log($revisionHistory);
    }

    public function logUpdateAction($revisionHistory, $model, $oldModel) {
        $revisionHistory->notes = $model->getModelTranslationAsUpdatedEntity($oldModel);
        $this->daoSource->log($revisionHistory);
    }

    public function logDeleteAction($revisionHistory, $model) {
        $revisionHistory->notes = $model->getModelTranslationAsDeletedEntity();
        $this->daoSource->log($revisionHistory);
    }

}
