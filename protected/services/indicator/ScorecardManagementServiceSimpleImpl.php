<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\service\indicator\ScorecardManagementService;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;
use org\csflu\isms\models\map\StrategyMap;

/**
 * Description of ScorecardManagementServiceSimpleImpl
 *
 * @author britech
 */
class ScorecardManagementServiceSimpleImpl implements ScorecardManagementService {

    private $daoSource;
    private $logger;

    public function __construct() {
        $this->daoSource = new MeasureProfileDao();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMeasureProfiles(StrategyMap $strategyMap) {
        return $this->daoSource->listMeasureProfiles($strategyMap);
    }

}
