<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\dao\commons\RevisionHistoryLoggingDao;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl;
use org\csflu\isms\core\DatabaseConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\util\ApplicationLoggerUtils;

/**
 *
 * @author britech
 */
class RevisionHistoryLoggingDaoSqlImpl implements RevisionHistoryLoggingDao {

    private $logger;
    private $userDao;
    private $db;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->userDao = new UserManagementDaoSqlImpl();
        $this->db = DatabaseConnectionManager::getInstance()->getMainDbConnection();
    }

    public function getRevisionHistoryList($moduleCode, $referenceId) {
        try {
            $params = array(
                'code' => $moduleCode, 
                'id' => $referenceId);
            $dbst = $this->db->prepare('SELECT module_code, module_id, user_ref, notes, rev_type, rev_stamp FROM rev_history WHERE module_code=:code AND module_id=:id ORDER BY rev_stamp DESC');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $revisions = array();
            while ($data = $dbst->fetch()) {
                $revision = new RevisionHistory();
                list($revision->module,
                        $revision->referenceId,
                        $user,
                        $revision->notes,
                        $revision->revisionType,
                        $date) = $data;
                $revision->employee = $this->userDao->getEmployeeData($user);
                $revision->revisionTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', $date);

                $revisions = array_merge($revisions, array($revision));
            }

            return $revisions;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function log(RevisionHistory $revisionHistory) {
        try {
            $this->db->beginTransaction();
            $params = array(
                'module' => $revisionHistory->module,
                'ref' => $revisionHistory->referenceId,
                'user' => $revisionHistory->employee->id,
                'notes' => $revisionHistory->notes,
                'type' => $revisionHistory->revisionType);
            $dbst = $this->db->prepare('INSERT INTO rev_history(module_code, module_id, user_ref, notes, rev_type) '
                    . 'VALUES(:module, :ref, :user, :notes, :type)');
            $dbst->execute($params);
            $this->db->commit();
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

}
