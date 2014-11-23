<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
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
     * @param string $id
     * @return StrategyMap
     * @throws DataAccessException
     */
    public function getStrategyMap($id);
    
    /**
     * @param Perspective $perspective
     * @return StrategyMap
     * @throws DataAccessException
     */
    public function getStrategyMapByPerspective($perspective);
    
    /**
     * @param StrategyMap $strategyMap
     * @return String
     * @throws DataAccessException
     */
    public function insert($strategyMap);
    
    /**
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function update($strategyMap);
}
