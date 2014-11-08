<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
/**
 *
 * @author britech
 */
interface StrategyMapManagementService {
    
    /**
     * Retrieves the list of available strategy maps
     * @return StrategyMap
     * @throws ServiceException
     */
    public function listStrategyMaps();
    
    /**
     * Inserts the Strategy Map entity to DataSource
     * @param StrategyMap $strategyMap
     * @return String auto-generated ID after insertion
     * @throws ServiceException
     */
    public function insert($strategyMap);
}
