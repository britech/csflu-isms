<?php

namespace org\csflu\isms\service\alignment;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\ubt\UnitBreakthrough;

/**
 *
 * @author britech
 */
interface StrategyAlignmentService {
    
    /**
     * Retrieves the Measure Profile that are aligned in the selected Objective entity
     * @param StrategyMap $strategyMap
     * @param Objective $objective
     * @return MeasureProfile
     */
    public function listAlignedMeasureProfiles(StrategyMap $strategyMap, Objective $objective);
    
    /**
     * Retrieves the Initiatives that are aligned in the selected Objective or MeasureProfile entity
     * @param StrategyMap $strategyMap
     * @param Objective $objective
     * @param MeasureProfile $measureProfile
     * @return Initiative[]
     */
    public function listAlignedInitiatives(StrategyMap $strategyMap, Objective $objective = null, MeasureProfile $measureProfile = null);
    
    /**
     * Retrieves the UnitBreakthroughs that are aligned in the selected Objective or MeasureProfile entity
     * @param StrategyMap $strategyMap
     * @param Objective $objective
     * @param MeasureProfile $measureProfile
     * @return UnitBreakthrough[]
     */
    public function listAlignedUnitBreakthroughs(StrategyMap $strategyMap, Objective $objective = null, MeasureProfile $measureProfile = null);
}
