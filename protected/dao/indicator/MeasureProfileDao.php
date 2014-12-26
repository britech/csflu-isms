<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface MeasureProfileDao {
    
    /**
     * @param StrategyMap $strategyMap
     * @return MeasureProfile[]
     * @throws DataAccessException
     */
    public function listMeasureProfiles(StrategyMap $strategyMap);
}
