<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Theme;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\ubt\UnitBreakthrough;

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
    public function insert(StrategyMap $strategyMap);

    /**
     * Updates a Strategy Map entity
     * @param StrategyMap $strategyMap
     */
    public function update(StrategyMap $strategyMap);

    /**
     * Retrieves the information about a Strategy Map
     * @param String $id Reference Id Search by ID
     * @param Perspective $perspective Search by Perspective
     * @param Objective $objective Search by Objective
     * @param Theme $theme Search by Theme
     * @param Initiative $initiative Search by Initiative
     * @param UnitBreakthrough $unitBreakthrough Search by UnitBreakthrough
     * @return StrategyMap
     */
    public function getStrategyMap($id = null, Perspective $perspective = null, Objective $objective = null, Theme $theme = null, Initiative $initiative = null, UnitBreakthrough $unitBreakthrough = null);

    /**
     * Retrieves the list of perspectives
     * @param StrategyMap $strategyMap optional
     * @return Perspective[]
     */
    public function listPerspectives(StrategyMap $strategyMap = null);

    /**
     * Insert the perspective in the selected strategy map
     * @param Perspective $perspective
     * @param StrategyMap $strategyMap
     * @throws ServiceException
     */
    public function insertPerspective(Perspective $perspective, StrategyMap $strategyMap);

    /**
     * Updates the perspective entity
     * @param Perspective $perspective
     * @throws ServiceException
     */
    public function updatePerspective(Perspective $perspective);

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

    /**
     * Retrieves the list of Strategic Themes
     * @param StrategyMap $strategyMap
     * @return Theme[]
     */
    public function listThemes(StrategyMap $strategyMap = null);

    /**
     * Manage insertion/update of a Strategic Theme
     * @param Theme $theme
     * @param StrategyMap $strategyMap
     * @throws ServiceException
     */
    public function manageTheme(Theme $theme, StrategyMap $strategyMap);

    /**
     * Deletes the Strategic theme
     * @param String $id
     */
    public function deleteTheme($id);

    /**
     * Retrieves the information of a selected Theme
     * @param String $id
     * @return Theme
     */
    public function getTheme($id);

    /**
     * Retrieves the list of objectives
     * @param StrategyMap $strategyMap
     * @return Objective[]
     */
    public function listObjectives(StrategyMap $strategyMap = null);

    /**
     * Retrieves the objective's information
     * @param String $id
     * @return Objective
     */
    public function getObjective($id);

    /**
     * Adds an Objective on a selected Strategy map
     * @param Objective $objective
     * @param StrategyMap $strategyMap
     * @throws ServiceException
     */
    public function addObjective(Objective $objective, StrategyMap $strategyMap);

    /**
     * Updates an Objective
     * @param Objective $objective
     * @throws ServiceException
     */
    public function updateObjective(Objective $objective);

    /**
     * Deletes an Objective
     * @param String $id
     */
    public function deleteObjective($id);
}
