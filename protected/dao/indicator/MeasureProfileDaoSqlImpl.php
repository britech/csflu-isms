<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\dao\indicator\MeasureProfileDao;
use org\csflu\isms\dao\indicator\IndicatorDaoSqlImpl;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;

/**
 * Description of MeasureProfileDaoSqlImpl
 *
 * @author britech
 */
class MeasureProfileDaoSqlImpl implements MeasureProfileDao {

    private $db;
    private $indicatorDataSource;
    private $objectiveDataSource;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->indicatorDataSource = new IndicatorDaoSqlImpl();
        $this->objectiveDataSource = new ObjectiveDaoSqlImpl();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMeasureProfiles(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT mp_id, measure_type, mp_freq, mp_stat, obj_ref, indicator_ref FROM mp_main JOIN smap_objectives ON obj_ref=obj_id WHERE map_ref=:map');
            $dbst->execute(array('map' => $strategyMap->id));

            $profiles = array();

            while ($data = $dbst->fetch()) {
                $measureProfile = new MeasureProfile();
                list($measureProfile->id, $measureProfile->measureType, $measureProfile->frequencyOfMeasure, $measureProfile->measureProfileEnvironmentStatus, $objective, $indicator) = $data;
                $measureProfile->indicator = $this->indicatorDataSource->retrieveIndicator($indicator);
                $measureProfile->objective = $this->objectiveDataSource->getObjective($objective);
                array_push($profiles, $measureProfile);
            }

            return $profiles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertMeasureProfile(MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO mp_main(obj_ref, indicator_ref, measure_type, mp_freq, mp_stat, period_start_date, period_end_date) VALUES(:objective, :indicator, :type, :frequency, :status, :start, :end)');
            $dbst->execute(array(
                'objective' => $measureProfile->objective->id,
                'indicator' => $measureProfile->indicator->id,
                'type' => $measureProfile->measureType,
                'frequency' => $measureProfile->frequencyOfMeasure,
                'status' => $measureProfile->measureProfileEnvironmentStatus,
                'start' => $measureProfile->timelineStart->format('Y-m-d'),
                'end' => $measureProfile->timelineEnd->format('Y-m-d')
            ));

            $id = $this->db->lastInsertId();

            $this->db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getMeasureProfile($id) {
        try {
            $dbst = $this->db->prepare('SELECT mp_id, measure_type, mp_freq, mp_stat, obj_ref, indicator_ref, period_start_date, period_end_date FROM mp_main WHERE mp_id=:id');
            $dbst->execute(array('id' => $id));

            $measureProfile = new MeasureProfile();

            while ($data = $dbst->fetch()) {
                list($measureProfile->id, $measureProfile->measureType, $measureProfile->frequencyOfMeasure, $measureProfile->measureProfileEnvironmentStatus, $objective, $indicator, $start, $end) = $data;
                $measureProfile->indicator = $this->indicatorDataSource->retrieveIndicator($indicator);
                $measureProfile->objective = $this->objectiveDataSource->getObjective($objective);
            }

            $measureProfile->timelineStart = \DateTime::createFromFormat('Y-m-d', $start);
            $measureProfile->timelineEnd = \DateTime::createFromFormat('Y-m-d', $end);
            
            return $measureProfile;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
