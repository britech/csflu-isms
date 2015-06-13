<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\dao\commons\RevisionHistoryLoggingDao;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\Employee;

/**
 *
 * @author britech
 */
class RevisionHistoryLoggingDaoSqlImpl implements RevisionHistoryLoggingDao {

    private $logger;
    private $userDao;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->userDao = new UserManagementDaoSqlImpl();
    }

    public function getRevisionHistoryList($moduleCode, $referenceId) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT module_code, module_id, user_ref, notes, rev_type, rev_stamp FROM rev_history WHERE module_code=:code AND module_id=:id ORDER BY rev_stamp DESC');
            $dbst->execute(array(
                'code' => $moduleCode,
                'id' => $referenceId
            ));

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
                
                array_push($revisions, $revision);
            }

            return $revisions;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function log($revisionHistory) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO rev_history(module_code, module_id, user_ref, notes, rev_type) '
                    . 'VALUES(:module, :ref, :user, :notes, :type)');
            $dbst->execute(array('module' => $revisionHistory->module,
                'ref' => $revisionHistory->referenceId,
                'user' => $revisionHistory->employee->id,
                'notes' => $revisionHistory->notes,
                'type' => $revisionHistory->revisionType));

            $db->commit();

            $this->logger->debug("EXECUTING SQL statement: {$dbst->queryString}");
            $this->logger->debug($revisionHistory);
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
