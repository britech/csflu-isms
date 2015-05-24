<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
use org\csflu\isms\models\indicator\MeasureProfileMovementLog;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl;

/**
 * Description of MeasureProfileMovementDaoSqlImpl
 *
 * @author britech
 */
class MeasureProfileMovementDaoSqlImpl implements MeasureProfileMovementDao {

    private $db;
    private $userDao;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->userDao = new UserManagementDaoSqlImpl();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function insertMovement(MeasureProfile $measureProfile, MeasureProfileMovement $movement) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO mp_movement VALUES(:profile, :date, :value)');
            $dbst->execute(array(
                'profile' => $measureProfile->id,
                'date' => $movement->periodDate->format('Y-m-d'),
                'value' => $movement->movementValue
            ));
            $this->insertMovementLog($measureProfile, $movement);
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listMovementLogs(MeasureProfile $measureProfile, MeasureProfileMovement $movement) {
        try {
            $dbst = $this->db->prepare('SELECT user_ref, notes, log_timestamp FROM mp_movement_log WHERE mp_ref=:profile AND period_date=:date');
            $dbst->execute(array(
                'profile' => $measureProfile->id,
                'date' => $movement->periodDate->format('Y-m-d')
            ));

            $movementLogs = array();
            while ($data = $dbst->fetch()) {
                $movementLog = new MeasureProfileMovementLog();
                list($user, $movementLog->notes, $date) = $data;

                $movementLog->user = $this->userDao->getUserAccount($user);
                $movementLog->dateEntered = \DateTime::createFromFormat('Y-m-d H:i:s', $date);

                $movementLogs = array_merge($movementLogs, array($movementLog));
            }
            return $movementLogs;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateMovement(MeasureProfile $measureProfile, MeasureProfileMovement $movement) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE mp_movement SET movement_value=:value WHERE mp_ref=:profile AND period_date=:date');
            $dbst->execute(array(
                'value' => $movement->movementValue,
                'profile' => $measureProfile->id,
                'date' => $movement->periodDate->format('Y-m-d')
            ));
            $this->insertMovementLog($measureProfile, $movement);
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    private function insertMovementLog(MeasureProfile $profile, MeasureProfileMovement $movement) {
        foreach ($movement->movementLogs as $movementLog) {
            $dbst = $this->db->prepare('INSERT INTO mp_movement_log(mp_ref, period_date, user_ref, notes) VALUES(:profile, :date, :user, :notes)');
            $dbst->execute(array(
                'profile' => $profile->id,
                'date' => $movement->periodDate->format('Y-m-d'),
                'user' => $movementLog->user->id,
                'notes' => $movementLog->notes
            ));
        }
    }

    public function listMeasureProfileMovements(MeasureProfile $measureProfile) {
        try {
            $dbst = $this->db->prepare('SELECT period_date, movement_value FROM mp_movement WHERE mp_ref=:profile');
            $dbst->execute(array(
                'profile' => $measureProfile->id
            ));

            $movements = array();

            while ($data = $dbst->fetch()) {
                $movement = new MeasureProfileMovement();
                list($date, $movement->movementValue) = $data;
                $movement->periodDate = \DateTime::createFromFormat('Y-m-d', $date);
                $movement->movementLogs = $this->listMovementLogs($measureProfile, $movement);

                $movements = array_merge($movements, array($movement));
            }
            return $movements;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
