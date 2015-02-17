<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface LeadMeasureDao {

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @throws DataAccessException
     */
    public function insertLeadMeasures(UnitBreakthrough $unitBreakthrough);
}
