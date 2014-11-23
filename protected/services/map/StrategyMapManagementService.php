<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Theme;

/**
 *
 * @author britech
 */
interface StrategyMapManagementService {

    /**
     * Retrieves the list of available strategy maps
     * @return StrategyMap
     */
    public function listStrategyMaps();

    /**
     * Enlists a Strategy Map entity
     * @param StrategyMap $strategyMap
     * @return String auto-generated ID after insertion
     */
    public function insert($strategyMap);
    
    /**
     * Updates a Strategy Map entity
     * @param StrategyMap $strategyMap
     */
    public function update($strategyMap);

    /**
     * Retrieves the information about a Strategy Map
     * @param String $id Reference Id Main search field
     * @param Perspective $perspective Optional
     * @param Objective $objective Optional
     * @param Theme $theme Optional
     * @return StrategyMap
     */
    public function getStrategyMap($id = null, $perspective = null, $objective = null, $theme = null);

    /**
     * Retrieves the list of perspectives
     * @param StrategyMap $strategyMap optional
     * @return Perspective[]
     */
    public function listPerspectives($strategyMap = null);

    /**
     * Insert the perspective in the selected strategy map
     * @param Perspective $perspective
     * @param StrategyMap $strategyMap
     * @throws ServiceException
     */
    public function insertPerspective($perspective, $strategyMap);
    
    /**
     * Updates the perspective entity
     * @param Perspective $perspective
     * @throws ServiceException
     */
    public function updatePerspective($perspective);
    
    /**
     * Deletes the perspective entity
     * @param String $id
     */
    public function deletePerspective($id);

    /**
     * Gets the information about the perspective
     * @param String $id
     * @return Perspective
     */
    public function getPerspective($id);
}
