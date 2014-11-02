<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;
/**
 *
 * @author britech
 */
interface BaselineDao {
    
    /**
     * @param Indicator $indicator
     * @throws DataAccessException
     */
    public function enlistBaseline($indicator);
    
    /**
     * @param Baseline $baseline
     * @throws DataAccessException
     */
    public function updateBaseline($baseline);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deleteBaseline($id);
}
