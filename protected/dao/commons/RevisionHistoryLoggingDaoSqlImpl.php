<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\dao\commons\RevisionHistoryLoggingDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\Employee;
/**
 *
 * @author britech
 */
class RevisionHistoryLoggingDaoSqlImpl implements RevisionHistoryLoggingDao {

    public function getRevisionHistoryList($moduleId) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT module_code, module_id, user_ref, notes, rev_type, rev_stamp, emp_lname, emp_fname '
                    . 'FROM rev_history '
                    . 'LEFT JOIN employees ON user_ref=emp_id'
                    . 'WHERE module_id=:id');
            $dbst->execute(array('id'=>$moduleId));
            
            $revisions = array();
            
            while($data = $dbst->fetch()){
                $revision = new RevisionHistory();
                $revision->employee = new Employee();
                list($revision->module,
                        $revision->referenceId,
                        $revision->employee->id,
                        $revision->notes,
                        $revision->revisionType,
                        $revision->revisionTimestamp,
                        $revision->employee->lastName,
                        $revision->employee->givenName) = $data;
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
            $dbst->execute(array('module'=>$revisionHistory->module,
                'ref'=>$revisionHistory->referenceId,
                'user'=>$revisionHistory->employee->id,
                'notes'=>$revisionHistory->notes,
                'type'=>$revisionHistory->revisionType));
            
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
