<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\service\initiative\InitiativeManagementService;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl as InitiativeDao;

/**
 * Description of InitiativeManagementServiceSimpleImpl
 *
 * @author britech
 */
class InitiativeManagementServiceSimpleImpl implements InitiativeManagementService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new InitiativeDao();
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        return $this->daoSource->listInitiatives($strategyMap);
    }

}
