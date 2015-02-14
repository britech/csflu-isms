<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\models\initiative\Activity;
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
     * @param String $id Load the initiative by its Identifier
     * @param Phase $phase Load the initiative by Phase
     * @return Initiative
     */
    public function getInitiative($id = null, Phase $phase = null);
    
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
    
    /**
     * Adds the linked objectives/lead measures in a given Initiative entity
     * @param Initiative $initiative
     * @return Initiative
     * @throws ServiceException
     */
    public function addAlignments(Initiative $initiative);
    
    /**
     * Unlinks the selected objective/lead measure in given Initiative entity
     * @param Initiative $initiative
     * @param Objective $objective
     * @param MeasureProfile $measureProfile
     * @throws ServiceException
     */
    public function unlinkAlignments(Initiative $initiative, Objective $objective = null, MeasureProfile $measureProfile = null);
    
    /**
     * Adds the Phase entity in a given Initiative
     * @param Phase $phase
     * @param Initiative $initiative
     * @throws ServiceException
     */
    public function addPhase(Phase $phase, Initiative $initiative);
    
    /**
     * Updates the Phase entity
     * @param Phase $phase
     * @throws ServiceException
     */
    public function updatePhase(Phase $phase);
    
    /**
     * Deletes the Phase entity
     * @param String $id
     */
    public function deletePhase($id);
    
    /**
     * Retrieves the Phase entity
     * @param String $id Retrieve by its identifier
     * @param Component $component Retrieve by its underlying Component object
     * @return Phase
     * @throws ServiceException
     */
    public function getPhase($id = null, Component $component = null);
        
    /**
     * Retrieves the Component entity
     * @param String $id Retrieve by its identifier
     * @param Activity $activity Retrieve by its underlying Component object
     * @return Component
     */
    public function getComponent($id = null, Activity $activity = null);
    
    /**
     * Enlists/Updates the component on a given phase entity
     * @param Component $component
     * @param Phase $phase
     * @throws ServiceException
     */
    public function manageComponent(Component $component, Phase $phase);
    
    /**
     * Deletes the component entity
     * @param String $id
     */
    public function deleteComponent($id);
    
    /**
     * Enlists/Updates the activity on a given component entity
     * @param Activity $activity
     * @param Component $component
     */
    public function manageActivity(Activity $activity, Component $component);
}
