<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\exceptions\ServiceException;

/**
 *
 * @author britech
 */
interface ScorecardManagementService {

    /**
     * Retrieves the list of Measure Profiles in a given Strategy Map
     * @param StrategyMap $strategyMap
     * @return MeasureProfile[]
     */
    public function listMeasureProfiles(StrategyMap $strategyMap);

    /**
     * Retrieves the selected Measure Profile
     * @param String $id
     * @return MeasureProfile
     */
    public function getMeasureProfile($id);

    /**
     * Inserts the Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @param StrategyMap $strategyMap
     * @return String auto-generated ID
     * @throws ServiceException
     */
    public function insertMeasureProfile(MeasureProfile $measureProfile, StrategyMap $strategyMap);

    /**
     * Inserts LeadOffice entities in a given Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function insertLeadOffices(MeasureProfile $measureProfile);
    
    /**
     * Inserts Target entities in a given Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function insertTargets(MeasureProfile $measureProfile);
}
