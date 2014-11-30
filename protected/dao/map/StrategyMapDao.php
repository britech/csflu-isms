<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;
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
    public function getStrategyMapByPerspective(Perspective $perspective);
    
    /**
     * @param Theme $theme
     * @return StrategyMap
     * @throws DataAccessException
     */
    public function getStrategyMapByTheme(Theme $theme);
    
    /**
     * @param Objective $objective
     * @return StrategyMap
     * @throws DataAccessException
     */
    public function getStrategyMapByObjective(Objective $objective);
    
    /**
     * @param StrategyMap $strategyMap
     * @return String
     * @throws DataAccessException
     */
    public function insert(StrategyMap $strategyMap);
    
    /**
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function update(StrategyMap $strategyMap);
}
