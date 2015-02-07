<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\initiative\Phase;
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
}
