<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;
use org\csflu\isms\exceptions\DataAccessException;

interface IndicatorDao {

    /**
     * @return Indicator[]
     * @throws DataAccessException
     */
    public function listIndicators();
    
    /**
     * @param Indicator $indicator
     * @return String
     * @throws DataAccessException
     */
    public function enlistIndicator($indicator);
    
    /**
     * @param Integer $id
     * @return Indicator
     * @throws DataAccessException
     */
    public function retrieveIndicator($id);
    
    /**
     * @param Indicator $indicator
     * @return Baseline[]
     * @throws DataAccessException
     */
    function retrieveIndicatorBaselineList($indicator);
    
    /**
     * @param Indicator $indicator
     * @throws DataAccessException
     */
    function updateIndicator($indicator);
}
