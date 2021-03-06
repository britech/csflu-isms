<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;

/**
 *
 * @author britech
 */
interface UnitBreakthroughDao {

    /**
     * @param Department $department
     * @return UnitBreakthrough[]
     * @throws DataAccessException
     */
    public function listUnitBreakthroughByDepartment(Department $department);

    /**
     * @param StrategyMap $strategyMap
     * @return UnitBreakthrough[]
     * @throws DataAccessException
     */
    public function listUnitBreakthroughByStrategyMap(StrategyMap $strategyMap);

    /**
     * @param String $id
     * @return UnitBreakthrough
     * @throws DataAccessException
     */
    public function getUnitBreakthroughByIdentifier($id);

    /**
     * @param LeadMeasure $leadMeasure
     * @return UnitBreakthrough
     * @throws DataAccessException
     */
    public function getUnitBreakthroughByLeadMeasure(LeadMeasure $leadMeasure);

    /**
     * @param WigSession $wigSession
     * @return UnitBreakthrough
     * @throws DataAccessException
     */
    public function getUnitBreakthroughByWigSession(WigSession $wigSession);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @param StrategyMap $strategyMap
     * @return String
     * @throws DataAccessException
     */
    public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @throws DataAccessException
     */
    public function updateUnitBreakthrough(UnitBreakthrough $unitBreakthrough);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @return Objective[]
     * @throws DataAccessException
     */
    public function listObjectives(UnitBreakthrough $unitBreakthrough);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @throws DataAccessException
     */
    public function linkObjectives(UnitBreakthrough $unitBreakthrough);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @param Objective $objective
     * @throws DataAccessException
     */
    public function unlinkObjective(UnitBreakthrough $unitBreakthrough, Objective $objective);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @return MeasureProfile[]
     * @throws DataAccessException
     */
    public function listMeasureProfiles(UnitBreakthrough $unitBreakthrough);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @throws DataAccessException
     */
    public function linkMeasureProfiles(UnitBreakthrough $unitBreakthrough);

    /**
     * @param UnitBreakthrough $unitBreakthrough
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function unlinkMeasureProfile(UnitBreakthrough $unitBreakthrough, MeasureProfile $measureProfile);
}
