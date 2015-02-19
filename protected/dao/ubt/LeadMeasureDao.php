<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\LeadMeasure;
/**
 *
 * @author britech
 */
interface LeadMeasureDao {

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @return LeadMeasure[]
     * @throws DataAccessException
     */
    public function listLeadMeasures(UnitBreakthrough $unitBreakthrough);
    
    /**
     * @param String $id
     * @return LeadMeasure
     * @throws DataAccessException
     */
    public function getLeadMeasure($id);
    
    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @throws DataAccessException
     */
    public function insertLeadMeasures(UnitBreakthrough $unitBreakthrough);
}
