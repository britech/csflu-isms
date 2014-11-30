<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\map\ObjectiveDao;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;

/**
 * Description of ObjectiveDaoSqlImpl
 *
 * @author britech
 */
class ObjectiveDaoSqlImpl implements ObjectiveDao{

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function listAllObjectives() {
        
    }

    public function listObjectivesByStrategyMap(StrategyMap $strategyMap) {
        
    }

    public function updateObjectivesCoveragePeriodsByStrategyMap(StrategyMap $strategyMap) {
        
    }

}
