<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface InitiativeDao {
    
    /**
     * @param StrategyMap $strategyMap
     * @return Initiative[]
     * @throws DataAccessException
     */
    public function listInitiatives(StrategyMap $strategyMap);
}
