<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\service\commons\RevisionHistoryLoggingService;
use org\csflu\isms\dao\commons\RevisionHistoryLoggingDaoSqlImpl as RevisionHistoryLoggingDao;
use org\csflu\isms\models\commons\RevisionHistory;

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

    public function log($revisionHistory, $model) {
        if ($revisionHistory->revisionType == RevisionHistory::TYPE_UPDATE) {
            $revisionHistory->notes = $model->getModelTranslationAsUpdatedEntity();
        } else {
            $revisionHistory->notes = $model->getModelTranslationAsNewEntity();
        }
        $this->daoSource->log($revisionHistory);
    }

}
