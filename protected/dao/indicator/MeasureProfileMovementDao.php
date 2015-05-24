<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
use org\csflu\isms\models\indicator\MeasureProfileMovementLog;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface MeasureProfileMovementDao {

    /**
     * @param MeasureProfile $measureProfile
     * @return MeasureProfileMovement[]
     * @throws DataAccessException
     */
    public function listMeasureProfileMovements(MeasureProfile $measureProfile);
    
    /**
     * @param MeasureProfile $measureProfile
     * @param MeasureProfileMovement $movement
     * @throws DataAccessException
     */
    public function insertMovement(MeasureProfile $measureProfile, MeasureProfileMovement $movement);

    /**
     * @param MeasureProfile $measureProfile
     * @param MeasureProfileMovement $movement
     * @throws DataAccessException
     */
    public function updateMovement(MeasureProfile $measureProfile, MeasureProfileMovement $movement);

    /**
     * @param MeasureProfile $measureProfile
     * @param MeasureProfileMovement $movement
     * @return MeasureProfileMovementLog[]
     * @throws DataAccessException
     */
    public function listMovementLogs(MeasureProfile $measureProfile, MeasureProfileMovement $movement);
}
