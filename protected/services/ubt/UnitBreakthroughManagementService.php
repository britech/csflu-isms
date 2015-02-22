<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
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
     * @param LeadMeasure $leadMeasure Retrieves by its underlying LeadMeasure entity
     * @return UnitBreakthrough
     */
    public function getUnitBreakthrough($id = null, LeadMeasure $leadMeasure = null);

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
}
