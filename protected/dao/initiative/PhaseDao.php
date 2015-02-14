<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface PhaseDao {

    /**
     * @param Initiative $initiative
     * @return Phase[]
     * @throws DataAccessException
     */
    public function listPhases(Initiative $initiative);
    
    /**
     * @param String $id
     * @return Phase
     * @throws DataAccessException
     */
    public function getPhaseByIdentifier($id);
    
    /**
     * @param Component $component
     * @return Phase
     * @throws DataAccessException
     */
    public function getPhaseByComponent(Component $component);
    
    /**
     * @param Phase $phase
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function addPhase(Phase $phase, Initiative $initiative);
    
    /**
     * @param Phase $phase
     * @throws DataAccessException
     */
    public function updatePhase(Phase $phase);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deletePhase($id);
}
