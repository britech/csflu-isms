<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface MeasureProfileDao {

    /**
     * @param StrategyMap $strategyMap
     * @return MeasureProfile[]
     * @throws DataAccessException
     */
    public function listMeasureProfiles(StrategyMap $strategyMap);

    /**
     * @param String $id
     * @return MeasureProfile
     * @throws DataAccessException
     */
    public function getMeasureProfile($id);

    /**
     * @param MeasureProfile $measureProfile
     * @return String
     * @throws DataAccessException
     */
    public function insertMeasureProfile(MeasureProfile $measureProfile);

    /**
     * @param MeasureProfile $measureProfile
     * @return LeadOffice[]
     * @throws DataAccessException
     */
    public function listLeadOffices(MeasureProfile $measureProfile);
}
