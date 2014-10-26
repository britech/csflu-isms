<?php

namespace org\csflu\isms\dao\commons;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface UnitOfMeasureDao {
    
    /**
     * @return UnitOfMeasure[]
     * @throws DataAccessException
     */
    public function listUnitOfMeasures();
}
