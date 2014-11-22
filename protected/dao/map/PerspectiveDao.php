<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\StrategyMap;

/**
 *
 * @author britech
 */
interface PerspectiveDao {

    /**
     * @return Perspective[]
     * @throws DataAccessException
     * */
    public function listAllPerspectives();

    /**
     * @param StrategyMap $strategyMap
     * @return Perspective[]
     * @throws DataAccessException
     * */
    public function listPerspectivesByStrategyMap($strategyMap);

    /**
     * @param String $id
     * @return Perspective
     * @throws DataAccessException
     */
    public function getPerspective($id);
    
    /**
     * @param Perspective $perspective
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function insertPerspective($perspective, $strategyMap);
    
    /**
     * @param Perspective $perspective
     * @throws DataAccessException
     */
    public function updatePerspective($perspective);
    
    /**
     * @param Perspective $perspective
     * @throws DataAccessException
     */
    public function deletePerspective($id);
}
