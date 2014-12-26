<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\map\StrategyMap;

/**
 *
 * @author britech
 */
interface ScorecardManagementService {

    /**
     * Retrieves the list of Measure Profiles in a given Strategy Map
     * @param StrategyMap $strategyMap
     * @return MeasureProfile[]
     */
    public function listMeasureProfiles(StrategyMap $strategyMap);
}
