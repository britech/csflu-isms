<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\exceptions\ServiceException;

/**
 *
 * @author britech
 */
interface InitiativeManagementService {

    /**
     * Lists Initiatives in a given StrategyMap
     * @param StrategyMap $strategyMap
     * @return Initiative[]
     */
    public function listInitiatives(StrategyMap $strategyMap);
    
    /**
     * Adds an Initiative in a given StrategyMap
     * @param Initiative $initiative
     * @param StrategyMap $strategyMap
     * @return String Auto-generated ID
     * @throws ServiceException
     */
    public function addInitiative(Initiative $initiative, StrategyMap $strategyMap);
    
    /**
     * Gets the selected Initiative
     * @param String $id
     * @return Initiative
     */
    public function getInitiative($id);
    
    /**
     * Updates the Initiative entity
     * @param Initiative $initiative
     * @throws ServiceException
     */
    public function updateInitiative(Initiative $initiative);
    
    /**
     * Adds implementing offices in a given initiative 
     * and returns the implementing offices that are to be added
     * @param Initiative $initiative
     * @return ImplementingOffice[]
     * @throws ServiceException
     */
    public function addImplementingOffices(Initiative $initiative);
    
    /**
     * Retrieves the ImplementingOffice entity
     * @param Initiative $initiative
     * @param String $id
     * @return ImplementingOffice
     */
    public function getImplementingOffice(Initiative $initiative, $id);
    
    /**
     * Deletes the ImplementingOffice
     * @param ImplementingOffice $implementingOffice
     */
    public function deleteImplementingOffice(ImplementingOffice $implementingOffice);
}
