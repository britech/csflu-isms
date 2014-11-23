<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;
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
    public function listPerspectivesByStrategyMap(StrategyMap $strategyMap);

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
    public function insertPerspective(Perspective $perspective, StrategyMap $strategyMap);
    
    /**
     * @param Perspective $perspective
     * @throws DataAccessException
     */
    public function updatePerspective(Perspective $perspective);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deletePerspective($id);
    
    /**
     * @return Theme[]
     * @throws DataAccessException
     */
    public function listAllThemes();
    
    /**
     * @param StrategyMap $strategyMap
     * @return Theme[]
     * @throws DataAccessException
     */
    public function listThemesByStrategyMap(StrategyMap $strategyMap);
    
    /**
     * @param Theme $theme
     * @param StrategyMap $strategyMap
     * @throws DataAccessException
     */
    public function insertTheme(Theme $theme, StrategyMap $strategyMap);
    
    /**
     * @param Theme $theme
     * @throws DataAccessException
     */
    public function updateTheme(Theme $theme);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deleteTheme($id);
    
    /**
     * @param String $id
     * @return Theme
     * @throws DataAccessException
     */
    public function getTheme($id);
}
