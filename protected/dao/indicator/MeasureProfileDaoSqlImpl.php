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
            $dbst = $this->db->prepare('SELECT mp_id, measure_type, mp_freq, mp_stat, obj_ref, indicator_ref WHERE map_ref=:map');
            $dbst->execute(array('map' => $strategyMap->id));

            $profiles = array();

            while ($data = $dbst->fetch()) {
                $measureProfile = new MeasureProfile();
                list($measureProfile->id, $measureProfile->measureType, $measureProfile->frequencyOfMeasure, $measureProfile->measureProfileEnvironmentStatus, $objective, $indicator) = $data;
                $measureProfile->indicator = $this->indicatorDataSource->retrieveIndicator($indicator);
                $measureProfile->objective = $this->objectiveDataSource->getObjective($objective);
                $profiles = array_merge(array($measureProfile));
            }

            return $profiles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
