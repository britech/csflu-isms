<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\service\commons\UnitOfMeasureService;
use org\csflu\isms\dao\commons\UnitOfMeasureDaoSqlImpl as UnitOfMeasureDao;

/**
 * Description of UnitOfMeasureSimpleImpl
 *
 * @author britech
 */
class UnitOfMeasureSimpleImpl implements UnitOfMeasureService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new UnitOfMeasureDao();
    }

    public function listUnitOfMeasures() {
        return $this->daoSource->listUnitOfMeasures();
    }

}
