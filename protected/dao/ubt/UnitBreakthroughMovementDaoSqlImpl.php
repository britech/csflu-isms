<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\WigMeeting;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\dao\ubt\UnitBreakthroughMovementDao;
use org\csflu\isms\core\ConnectionManager;

/**
 * Description of UniBreakthroughMovementDaoSqlImpl
 *
 * @author britech
 */
class UnitBreakthroughMovementDaoSqlImpl implements UnitBreakthroughMovementDao {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function enlistUbtMovement(WigSession $wigSession) {
        try {
            $this->db->beginTransaction();

            $meetingDbst = $this->db->prepare('UPDATE ubt_wig SET actual_start_date=:start_date, actual_end_date=:end_date, meeting_venue=:venue, meeting_time_start=:time_start, meeting_time_end=:time_end, status=:status WHERE wig_id=:id');
            $meetingDbst->execute(array(
                'start_date' => $wigSession->wigMeeting->actualSessionStartDate->format('Y-m-d'),
                'end_date' => $wigSession->wigMeeting->actualSessionEndDate->format('Y-m-d'),
                'venue' => $wigSession->wigMeeting->meetingVenue,
                'time_start' => $wigSession->wigMeeting->meetingTimeStart->format('H:i:s'),
                'time_end' => $wigSession->wigMeeting->meetingTimeEnd->format('H:i:s'),
                'status' => $wigSession->wigMeetingEnvironmentStatus,
                'id' => $wigSession->id
            ));

            $movementDbst = $this->db->prepare('INSERT INTO ubt_movement(wig_ref, ubt_figure, lm1_figure, lm2_figure, notes) VALUES(:wig, :ubt, :lm1, :lm2, :notes)');
            $movementDbst->execute(array(
                'wig' => $wigSession->id,
                'ubt' => $wigSession->movementUpdate->ubtFigure,
                'lm1' => $wigSession->movementUpdate->firstLeadMeasureFigure,
                'lm2' => $wigSession->movementUpdate->secondLeadMeasureFigure,
                'notes' => $wigSession->movementUpdate->notes
            ));

            $commitmentsDbst = $this->db->prepare('UPDATE commitments_main SET status=:status WHERE status IN (:stat1, :stat2) AND wig_ref=:wig');
            $commitmentsDbst->execute(array(
                'status' => Commitment::STATUS_UNFINISHED,
                'stat1' => Commitment::STATUS_PENDING,
                'stat2' => Commitment::STATUS_ONGOING,
                'wig' => $wigSession->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function retrieveUbtMovementDataByIdentifier($id) {
        try {
            $dbst = $this->db->prepare('SELECT movement_id, date_entered, ubt_figure, lm1_figure, lm2_figure, notes FROM ubt_movement WHERE movement_id=:id');
            $dbst->execute(array('id' => $id));

            $ubtMovement = new UnitBreakthroughMovement();
            while ($data = $dbst->fetch()) {
                list($ubtMovement->id,
                        $timestamp,
                        $ubtMovement->ubtFigure,
                        $ubtMovement->firstLeadMeasureFigure,
                        $ubtMovement->secondLeadMeasureFigure,
                        $ubtMovement->notes) = $data;
            }
            $ubtMovement->dateEntered = \DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
            return $ubtMovement;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function retrieveWigMeetingData(WigSession $wigSession) {
        try {
            $dbst = $this->db->prepare('SELECT actual_start_date, actual_end_date, meeting_venue, meeting_time_start, meeting_time_end FROM ubt_wig WHERE wig_id=:id');
            $dbst->execute(array('id' => $wigSession->id));

            $wigMeeting = new WigMeeting();
            while ($data = $dbst->fetch()) {
                list($startDate,
                        $endDate,
                        $wigMeeting->meetingVenue,
                        $timeStart,
                        $timeEnd) = $data;
            }

            $wigMeeting->actualSessionStartDate = \DateTime::createFromFormat('Y-m-d', $startDate);
            $wigMeeting->actualSessionEndDate = \DateTime::createFromFormat('Y-m-d', $endDate);
            $wigMeeting->meetingTimeStart = \DateTime::createFromFormat('H:i:s', $timeStart);
            $wigMeeting->meetingTimeEnd = \DateTime::createFromFormat('H:i:s', $timeEnd);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function retrieveUbtMovementDataByWigSession(WigSession $wigSession) {
        try {
            $dbst = $this->db->prepare('SELECT movement_id FROM ubt_movement WHERE wig_ref=:wig');
            $dbst->execute(array('wig' => $wigSession->id));

            while ($data = $dbst->fetch()) {
                list($id) = $data;
            }

            return $this->retrieveUbtMovementDataByIdentifier($id);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
