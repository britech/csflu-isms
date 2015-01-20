<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
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
    public function listInitiatives(StrategyMap $strategyMap);
    
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
    public function linkObjectives(Initiative $initiative);
    
    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function linkLeadMeasures(Initiative $initiative);
    
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
    
    /**
     * @param String $id
     * @return Initiative
     * @throws DataAccessException
     */
    public function getInitiative($id);
    
    /**
     * @param Initiative $initiative
     * @throws DataAccessException
     */
    public function updateInitiative(Initiative $initiative);
}
