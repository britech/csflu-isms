<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;

/**
 *
 * @author britech
 */
interface IndicatorManagementService {
    
    /**
     * Retrieves the list of enlisted indicators
     * @return Indicator[]
     * @throws ServiceException
     */
    public function listIndicators();
    
    /**
     * Enlists an Indicator entity
     * @param Indicator $indicator
     * @return String auto-generated ID after enlistment
     * @throws ServiceException
     */
    public function enlistIndicator($indicator);
    
    /**
     * Retrieves the Indicator entity
     * @param String $id Indicator Identifier
     * @return Indicator
     * @throws ServiceException
     */
    public function retrieveIndicator($id);
    
    /**
     * Updates an Indicator entity
     * @param Indicator $indicator
     * @throws ServiceException
     */
    public function updateIndicator($indicator);
    
    /**
     * Retrieves the desired indicator based on the Indicator entity
     * @param Indicator $indicator
     * @param String $id
     * @return Baseline
     * @throws ServiceException
     */
    public function getBaselineDataFromIndicator($indicator, $id);
    
    /**
     * Attach a Baseline entity to an Indicator entity
     * @param Indicator $indicator
     * @throws ServiceException
     */
    public function addBaselineDataToIndicator($indicator);
    
    /**
     * Updates the Baseline entity
     * @param Baseline $baseline
     * @throws ServiceException
     */
    public function updateBaseline($baseline);
    
    /**
     * Deletes the Baseline entity
     * @param String $id
     * @throws ServiceException
     */
    public function unlinkBaseline($id);
}
