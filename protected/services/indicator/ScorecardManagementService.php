<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\Target;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
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
     * @param String $id Search the profile by its identifier
     * @param LeadOffice $leadOffice Search the profile by its underlying LeadOffice
     * @param Target $target Search the profile by its underlying Target
     * @return MeasureProfile
     */
    public function getMeasureProfile($id = null, LeadOffice $leadOffice = null, Target $target = null);

    /**
     * Inserts the Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @param StrategyMap $strategyMap
     * @return String auto-generated ID
     * @throws ServiceException
     */
    public function insertMeasureProfile(MeasureProfile $measureProfile, StrategyMap $strategyMap);

    /**
     * Updates the MeasureProfile entity
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function updateMeasureProfile(MeasureProfile $measureProfile);

    /**
     * Gets the Lead Office entity under the selected Measure Profile
     * @param MeasureProfile $measureProfile
     * @param String $id
     * @return LeadOffice
     * @throws ServiceException
     */
    public function getLeadOffice(MeasureProfile $measureProfile, $id);

    /**
     * Inserts LeadOffice entities in a given Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function insertLeadOffices(MeasureProfile $measureProfile);

    /**
     * Updates the LeadOffice entity
     * @param LeadOffice $leadOffice
     */
    public function updateLeadOffice(LeadOffice $leadOffice);

    /**
     * Deletes the LeadOffice entity
     * @param LeadOffice $id
     */
    public function deleteLeadOffice($id);

    /**
     * Inserts Target entities in a given Measure Profile entity
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function insertTargets(MeasureProfile $measureProfile);

    /**
     * Updates the Target entity
     * @param Target $target
     */
    public function updateTarget(Target $target);

    /**
     * Deletes the Target entity
     * @param String $id
     */
    public function deleteTarget($id);

    /**
     * Retrieves a Target entity under the selected Measure Profile
     * @param MeasureProfile $measureProfile
     * @param String $id
     * @return Target Description
     */
    public function getTarget(MeasureProfile $measureProfile, $id);

    /**
     * Enlists the movement of the selected MeasureProfile entity
     * @param MeasureProfile $measureProfile
     * @param MeasureProfileMovement $measureProfileMovement
     */
    public function enlistMovement(MeasureProfile $measureProfile, MeasureProfileMovement $measureProfileMovement);

    /**
     * Updates the enlisted movement of the selected MeasureProfile entity
     * @param MeasureProfile $measureProfile
     * @param MeasureProfileMovement $measureProfileMovement
     */
    public function updateMovement(MeasureProfile $measureProfile, MeasureProfileMovement $measureProfileMovement);
}
