<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;

/**
 *
 * @author britech
 */
interface UnitBreakthroughManagementService {

    /**
     * Lists the UnitBreakthroughs enlisted under a specified StrategyMap or Department entity
     * @param StrategyMap $strategyMap
     * @param Department $department
     * @return UnitBreakthrough[]
     */
    public function listUnitBreakthrough(StrategyMap $strategyMap = null, Department $department = null);

    /**
     * Retrieves the UnitBreakthrough entity
     * @param String $id Retrieve by its identifier
     * @param LeadMeasure $leadMeasure Retrieve by its underlying LeadMeasure entity
     * @param WigSession $wigSession Retrieve by its underlying WigSession entity
     * @return UnitBreakthrough
     */
    public function getUnitBreakthrough($id = null, LeadMeasure $leadMeasure = null, WigSession $wigSession = null);

    /**
     * Insert the UnitBreakthrough with its aligned StrategyMap entity
     * @param UnitBreakthrough $unitBreakthrough
     * @param StrategyMap $strategyMap
     * @return String auto-generated Id
     * @throws ServiceException
     */
    public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap);

    /**
     * Updates the UnitBreakthrough entity
     * @param UnitBreakthrough $unitBreakthrough
     * @param StrategyMap $strategyMap
     * @throws ServiceException
     */
    public function updateUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap);

    /**
     * Retrieves the defined LeadMeasure
     * @param String $id
     * @return LeadMeasure
     */
    public function retrieveLeadMeasure($id);

    /**
     * Inserts the LeadMeasure entity using an UnitBreakthrough entity
     * @param UnitBreakthrough $unitBreakthrough
     * @return LeadMeasure[] inserted LeadMeasure entities
     * @throws ServiceException
     */
    public function insertLeadMeasures(UnitBreakthrough $unitBreakthrough);
    
    /**
     * Updates the LeadMeasure entity
     * @param LeadMeasure $leadMeasure
     * @throws ServiceException
     */
    public function updateLeadMeasure(LeadMeasure $leadMeasure);

    /**
     * Updates the environment status of the LeadMeasure entity
     * @param LeadMeasure $leadMeasure
     * @throws ServiceException
     */
    public function updateLeadMeasureStatus(LeadMeasure $leadMeasure);
    
    /**
     * Links the selected objectives/measures profiles in a given UnitBreakthrough entity
     * @param UnitBreakthrough $unitBreakthrough
     * @return UnitBreakthrough
     * @throws ServiceException
     */
    public function addAlignments(UnitBreakthrough $unitBreakthrough);

    /**
     * Deletes the selected objective/measure profile in a given UnitBreakthrough entity
     * @param UnitBreakthrough $unitBreakthrough
     * @param Objective $objective
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function deleteAlignments(UnitBreakthrough $unitBreakthrough, Objective $objective = null, MeasureProfile $measureProfile = null);

    /**
     * Retrieves the WIG Session
     * @param String $id Retrieve by its identifier
     * @return WigSession
     */
    public function getWigSessionData($id);

    /**
     * Inserts the WIG Session on a given UnitBreakthrough entity
     * @param WigSession $wigSession
     * @param UnitBreakthrough $unitBreakthrough
     * @return String Auto-generated ID
     * @throws ServiceException
     */
    public function insertWigSession(WigSession $wigSession, UnitBreakthrough $unitBreakthrough);

    /**
     * Updates the WigSession entity
     * @param WigSession $wigSession
     * @throws ServiceException
     */
    public function updateWigSession(WigSession $wigSession);
    
    /**
     * Deletes the WigSession entity
     * @param String $id
     */
    public function deleteWigSession($id);
}
