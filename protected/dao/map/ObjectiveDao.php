<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
/**
 *
 * @author britech
 */
interface ObjectiveDao {
    
    /**
     * @return Objective[]
     * @throws DataAccessException
     */
    public function listAllObjectives();
    
    /**
     * @param StrategyMap $strategyMap
     * @return Objective[]
     * @throws DataAccessException
     */
    public function listObjectivesByStrategyMap(StrategyMap $strategyMap);
    
    /**
     * @param String $id
     * @return Objective
     * @throws DataAccessException
     */
    public function getObjective($id);
    
    /**
     * @param Objective $objective
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function addObjective(Objective $objective, StrategyMap $strategyMap);
    
    /**
     * @param Objective $objective
     * @throws DataAccessException
     */
    public function updateObjective(Objective $objective);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deleteObjective($id);
    
    /**
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function updateObjectivesCoveragePeriodsByStrategyMap(StrategyMap $strategyMap);
}
