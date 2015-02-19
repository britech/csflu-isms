<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementService;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\dao\ubt\UnitBreakthroughDaoSqlImpl as UnitBreakthroughDao;

/**
 *
 * @author britech
 */
class UnitBreakthroughManagementServiceSimpleImpl implements UnitBreakthroughManagementService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new UnitBreakthroughDao();
    }

    public function getUnitBreakthrough($id) {
        return $this->daoSource->getUnitBreakthroughByIdentifier($id);
    }

    public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap) {
        $unitBreakthroughs = $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);

        foreach ($unitBreakthroughs as $data) {
            if ($unitBreakthrough->unit->id == $data->unit->id && $unitBreakthrough->description == $data->description) {
                throw new ServiceException("UnitBreakthrough already defined. Please use the update facility instead");
            }
        }
        return $this->daoSource->insertUnitBreakthrough($unitBreakthrough, $strategyMap);
    }

    public function listUnitBreakthrough(StrategyMap $strategyMap) {
        return $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);
    }

}
