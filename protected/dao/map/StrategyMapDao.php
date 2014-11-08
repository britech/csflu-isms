<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
/**
 *
 * @author britech
 */
interface StrategyMapDao {
    
    /**
     * @return StrategyMap[]
     * @throws DataAccessException
     */
    public function listStrategyMaps();
    
    /**
     * @param StrategyMap $strategyMap
     * @return String
     * @throws DataAccessException
     */
    public function insert($strategyMap);
}
