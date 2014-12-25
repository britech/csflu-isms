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
     * Enlists/Updates an Indicator entity
     * @param Indicator $indicator
     * @throws ServiceException
     */
    public function manageIndicator(Indicator $indicator);

    /**
     * Retrieves the Indicator entity
     * @param String $id
     * @param Baseline $baseline optional
     * @return Indicator
     * @throws ServiceException
     */
    public function retrieveIndicator($id = null, Baseline $baseline = null);

    /**
     * Retrieves the Baseline entity
     * @param String $id
     * @return Baseline
     */
    public function getBaseline($id);

    /**
     * Adds a baseline in a selected Indicator
     * @param Baseline $baseline
     * @param Indicator $indicator
     * @throws ServiceException
     */
    public function addBaseline(Baseline $baseline, Indicator $indicator);

    /**
     * Updates the Baseline entity
     * @param Baseline $baseline
     * @throws ServiceException
     */
    public function updateBaseline(Baseline $baseline);

    /**
     * Deletes the Baseline entity
     * @param String $id
     * @throws ServiceException
     */
    public function unlinkBaseline($id);
}
