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
    
    /**
     * @param UnitOfMeasure $uom
     * @throws DataAccessException
     */
    public function enlistUom(UnitOfMeasure $uom);
    
    /**
     * @param UnitOfMeasure $uom
     * @throws DataAccessException
     */
    public function updateUom(UnitOfMeasure $uom);
    
    /**
     * @return UnitOfMeasure
     * @throws DataAccessException
     */
    public function getUomInfo($id);
}
