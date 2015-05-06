<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\dao\ubt\WigSessionDao;
use org\csflu\isms\dao\ubt\CommitmentCrudDaoSqlImpl;
use org\csflu\isms\dao\ubt\UnitBreakthroughMovementDaoSqlImpl;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\Commitment;

/**
 * Description of WigMeetingDaoSqlImpl
 *
 * @author britech
 */
class WigSessionDaoSqlmpl implements WigSessionDao {

    private $db;
    private $commitCrudDaoSource;
    private $movementDaoSource;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->commitCrudDaoSource = new CommitmentCrudDaoSqlImpl();
        $this->movementDaoSource = new UnitBreakthroughMovementDaoSqlImpl();
    }

    public function insertWigSession(WigSession $wigSession, UnitBreakthrough $unitBreakthrough) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('INSERT INTO ubt_wig(ubt_ref, period_start_date, period_end_date, status) VALUES(:ubt, :start, :end, :status)');
            $dbst->execute(array(
                'ubt' => $unitBreakthrough->id,
                'start' => $wigSession->startingPeriod->format('Y-m-d'),
                'end' => $wigSession->endingPeriod->format('Y-m-d'),
                'status' => $wigSession->wigMeetingEnvironmentStatus
            ));

            $id = $this->db->lastInsertId();
            $this->db->commit();
            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listWigSessions(UnitBreakthrough $unitBreakthrough) {
        try {
            $dbst = $this->db->prepare('SELECT wig_id FROM ubt_wig WHERE ubt_ref=:ubt ORDER BY period_start_date');
            $dbst->execute(array('ubt' => $unitBreakthrough->id));

            $wigMeetings = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                array_push($wigMeetings, $this->getWigSessionData($id));
            }
            return $wigMeetings;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getWigSessionData($id) {
        try {
            $dbst = $this->db->prepare('SELECT wig_id, period_start_date, period_end_date, status FROM ubt_wig WHERE wig_id=:id');
            $dbst->execute(array('id' => $id));

            $wigSession = new WigSession();
            while ($data = $dbst->fetch()) {
                list($wigSession->id, $startDate, $endDate, $wigSession->wigMeetingEnvironmentStatus) = $data;
            }
            $wigSession->startingPeriod = \DateTime::createFromFormat('Y-m-d', $startDate);
            $wigSession->endingPeriod = \DateTime::createFromFormat('Y-m-d', $endDate);
            $wigSession->commitments = $this->commitCrudDaoSource->listCommitments($wigSession);
            $wigSession->wigMeeting = $this->movementDaoSource->retrieveWigMeetingData($wigSession);
            $wigSession->movementUpdates = $this->movementDaoSource->listUnitBreakthroughMovements($wigSession);
            
            return $wigSession;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateWigSession(WigSession $wigSession) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('UPDATE ubt_wig SET period_start_date=:start, period_end_date=:end, status=:status WHERE wig_id=:id');
            $dbst->execute(array(
                'start' => $wigSession->startingPeriod->format('Y-m-d'),
                'end' => $wigSession->endingPeriod->format('Y-m-d'),
                'status' => $wigSession->wigMeetingEnvironmentStatus,
                'id' => $wigSession->id
            ));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteWigSession($id) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('DELETE FROM ubt_wig WHERE wig_id=:id');
            $dbst->execute(array(
                'id' => $id
            ));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getWigSessionDataByCommitment(Commitment $commitment) {
        try {
            $dbst = $this->db->prepare('SELECT wig_ref FROM commitments_main WHERE commit_id=:id');
            $dbst->execute(array(
                'id'=>$commitment->id
            ));
            
            while($data = $dbst->fetch()){
                list($id) = $data;
            }
            return $this->getWigSessionData($id);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
