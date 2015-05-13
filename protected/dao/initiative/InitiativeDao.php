<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface InitiativeDao {

    /**
     * @param StrategyMap $strategyMap
     * @return Initiative[]
     * @throws DataAccessException
     */
    public function listInitiativesByStrategyMap(StrategyMap $strategyMap);

    /**
     * @param ImplementingOffice $implementingOffice
     * @return Initiative[]
     * @throws DataAccessException
     */
    public function listInitiativesByImplementingOffice(ImplementingOffice $implementingOffice);

    /**
     * @param Initiative $initiative
     * @param StrategyMap $strategyMap
     * @return String
     * @throws DataAccessException
     */
    public function insertInitiative(Initiative $initiative, StrategyMap $strategyMap);

    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function updateInitiative(Initiative $initiative);

    /**
     * @param String $id
     * @return Initiative
     * @throws DataAccessException
     */
    public function getInitiative($id);

    /**
     * @param Phase $phase
     * @return Initiative
     * @throws DataAccessException
     */
    public function getInitiativeByPhase(Phase $phase);

    /**
     * @param Initiative $initiative
     * @return Objective[]
     * @throws DataAccessException
     */
    public function listObjectives(Initiative $initiative);

    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function linkObjectives(Initiative $initiative);

    /**
     * @param Initiative $initiative
     * @param Objective $objective
     * @throws DataAccessException
     */
    public function unlinkObjective(Initiative $initiative, Objective $objective);

    /**
     * @param Initiative $initiative
     * @return MeasureProfile[]
     * @throws DataAccessException
     */
    public function listLeadMeasures(Initiative $initiative);

    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function linkLeadMeasures(Initiative $initiative);

    /**
     * @param Initiative $initiative
     * @param MeasureProfile $measureProfile
     * @throws DataAccessException
     */
    public function unlinkLeadMeasure(Initiative $initiative, MeasureProfile $measureProfile);

    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function addImplementingOffices(Initiative $initiative);

    /**
     * @param ImplementingOffice $implementingOffice
     * @throws DataAccessException
     */
    public function deleteImplementingOffice(ImplementingOffice $implementingOffice);

    /**
     * @param Initiative $initiative
     * @return ImplementingOffice[]
     * @throws DataAccessException
     */
    public function listImplementingOffices(Initiative $initiative);
}
