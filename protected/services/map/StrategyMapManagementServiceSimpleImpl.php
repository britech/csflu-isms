<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\service\map\StrategyMapManagementService;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;

/**
 *
 *
 * @author britech
 */
class StrategyMapManagementServiceSimpleImpl implements StrategyMapManagementService {

    private $mapDaoSource;
    public function __construct() {
        $this->mapDaoSource = new StrategyMapDao();
    }
    
    public function listStrategyMaps() {
        return $this->mapDaoSource->listStrategyMaps();
    }

    public function insert($strategyMap) {
        return $this->mapDaoSource->insert($strategyMap);
    }

}
