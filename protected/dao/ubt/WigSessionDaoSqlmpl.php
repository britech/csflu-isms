<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\dao\ubt\WigSessionDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthrough;

/**
 * Description of WigMeetingDaoSqlImpl
 *
 * @author britech
 */
class WigSessionDaoSqlmpl implements WigSessionDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
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

            $wigMeeting = new WigSession();
            while ($data = $dbst->fetch()) {
                list($wigMeeting->id, $startDate, $endDate, $wigMeeting->wigMeetingEnvironmentStatus) = $data;
            }
            $wigMeeting->startingPeriod = \DateTime::createFromFormat('Y-m-d', $startDate);
            $wigMeeting->endingPeriod = \DateTime::createFromFormat('Y-m-d', $endDate);
            return $wigMeeting;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
