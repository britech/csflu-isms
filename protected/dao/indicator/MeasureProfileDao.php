<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\LeadOffice;
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
     * @param LeadOffice $leadOffice
     * @return MeasureProfile
     * @throws DataAccessException
     */
    public function getMeasureProfileByLeadOffice(LeadOffice $leadOffice);

    /**
     * @param MeasureProfile $measureProfile
     * @return String
     * @throws DataAccessException
     */
    public function insertMeasureProfile(MeasureProfile $measureProfile);
    
    /**
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function updateMeasureProfile(MeasureProfile $measureProfile);

    /**
     * @param MeasureProfile $measureProfile
     * @return LeadOffice[]
     * @throws DataAccessException
     */
    public function listLeadOffices(MeasureProfile $measureProfile);

    /**
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function insertLeadOffices(MeasureProfile $measureProfile);
    
    /**
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function insertTargets(MeasureProfile $measureProfile);
    
    /**
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function listTargets(MeasureProfile $measureProfile);
}
