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
use org\csflu\isms\service\initiative\InitiativeManagementService;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl as InitiativeDao;
use org\csflu\isms\dao\initiative\PhaseDaoSqlImpl as PhaseDao;
use org\csflu\isms\dao\initiative\ComponentDaoSqlImpl as ComponentDao;
use org\csflu\isms\dao\initiative\ActivityDaoSqlImpl as ActivityDao;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl as ObjectiveDao;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;
use org\csflu\isms\dao\initiative\ActivityMovementDaoSqlImpl as ActivityMovementDao;

/**
 * Description of InitiativeManagementServiceSimpleImpl
 *
 * @author britech
 */
class InitiativeManagementServiceSimpleImpl implements InitiativeManagementService {

    private $logger;
    private $daoSource;
    private $phaseDaoSource;
    private $componentDaoSource;
    private $activityDaoSource;
    private $mapDaoSource;
    private $objectiveDaoSource;
    private $mpDaoSource;
    private $movementDaoSource;

    public function __construct() {
        $this->daoSource = new InitiativeDao();
        $this->phaseDaoSource = new PhaseDao();
        $this->componentDaoSource = new ComponentDao();
        $this->activityDaoSource = new ActivityDao();
        $this->mapDaoSource = new StrategyMapDao();
        $this->objectiveDaoSource = new ObjectiveDao();
        $this->mpDaoSource = new MeasureProfileDao();
        $this->movementDaoSource = new ActivityMovementDao();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listInitiatives(StrategyMap $strategyMap = null, ImplementingOffice $implementingOffice = null) {
        if (!is_null($strategyMap)) {
            return $this->daoSource->listInitiativesByStrategyMap($strategyMap);
        } elseif (!is_null($implementingOffice)) {
            return $this->daoSource->listInitiativesByImplementingOffice($implementingOffice);
        }
    }

    public function addInitiative(Initiative $initiative, StrategyMap $strategyMap) {
        $initiatives = $this->daoSource->listInitiativesByStrategyMap($strategyMap);

        $found = false;
        foreach ($initiatives as $data) {
            if ($data->title == $initiative->title) {
                $found = true;
                break;
            }
        }

        if ($found) {
            throw new ServiceException("Initiative already defined. Please use the update facility instead");
        }
        $initiative->id = $this->daoSource->insertInitiative($initiative, $strategyMap);
        $this->daoSource->addImplementingOffices($initiative);
        $this->daoSource->linkObjectives($initiative);
        $this->daoSource->linkLeadMeasures($initiative);
        return $initiative->id;
    }

    public function getInitiative($id = null, Phase $phase = null) {
        if (!is_null($id)) {
            return $this->daoSource->getInitiative($id);
        }

        if (!is_null($phase)) {
            return $this->daoSource->getInitiativeByPhase($phase);
        }
    }

    public function updateInitiative(Initiative $initiative) {
        $strategyMap = $this->mapDaoSource->getStrategyMapByInitiative($initiative);
        $initiatives = $this->daoSource->listInitiativesByStrategyMap($strategyMap);

        $found = false;
        foreach ($initiatives as $data) {
            if ($data->id != $initiative->id && $data->title == $initiative->title) {
                $found = true;
                break;
            }
        }

        if ($found) {
            throw new ServiceException("Initiative not updated. Invalid argument data");
        }
        $this->daoSource->updateInitiative($initiative);
    }

    public function addImplementingOffices(Initiative $initiative) {
        $implementingOffices = $this->daoSource->listImplementingOffices($initiative);

        $implementingOfficesToInsert = array();
        foreach ($initiative->implementingOffices as $implementingOffice) {
            $found = false;
            foreach ($implementingOffices as $data) {
                if ($implementingOffice->department->id == $data->department->id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($implementingOfficesToInsert, $implementingOffice);
            }
        }
        if (count($implementingOfficesToInsert) == 0) {
            throw new ServiceException("No Implementing Offices added");
        }
        $initiative->implementingOffices = $implementingOfficesToInsert;
        $this->daoSource->addImplementingOffices($initiative);
        return $implementingOfficesToInsert;
    }

    public function deleteImplementingOffice(ImplementingOffice $implementingOffice) {
        $this->daoSource->deleteImplementingOffice($implementingOffice);
    }

    public function getImplementingOffice(Initiative $initiative, $id) {
        $implementingOffices = $this->daoSource->listImplementingOffices($initiative);

        foreach ($implementingOffices as $implementingOffice) {
            if ($implementingOffice->id == $id) {
                return $implementingOffice;
            }
        }
    }

    public function addAlignments(Initiative $initiative) {
        $initiative->objectives = $this->filterAlignedObjectives($initiative);
        $initiative->leadMeasures = $this->filterAlignedLeadMeasures($initiative);

        if (count($initiative->objectives) == 0 && count($initiative->leadMeasures) == 0) {
            throw new ServiceException("No Strategy Alignments performed");
        }

        if (count($initiative->objectives) > 0) {
            $this->daoSource->linkObjectives($initiative);
        }

        if (count($initiative->leadMeasures) > 0) {
            $this->daoSource->linkLeadMeasures($initiative);
        }
        return $initiative;
    }

    private function filterAlignedObjectives(Initiative $initiative) {
        $linkedObjectives = $this->daoSource->listObjectives($initiative);
        $objectivesToLink = array();
        foreach ($initiative->objectives as $objective) {
            $objective = $this->objectiveDaoSource->getObjective($objective->id);
            $found = false;
            foreach ($linkedObjectives as $data) {
                if ($data->id == $objective->id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($objectivesToLink, $objective);
            }
        }
        return $objectivesToLink;
    }

    private function filterAlignedLeadMeasures(Initiative $initiative) {
        $linkedMeasures = $this->daoSource->listLeadMeasures($initiative);
        $measuresToLink = array();
        foreach ($initiative->leadMeasures as $leadMeasure) {
            $leadMeasure = $this->mpDaoSource->getMeasureProfile($leadMeasure->id);
            $found = false;
            foreach ($linkedMeasures as $data) {
                if ($data->id == $leadMeasure->id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($measuresToLink, $leadMeasure);
            }
        }
        return $measuresToLink;
    }

    public function unlinkAlignments(Initiative $initiative, Objective $objective = null, MeasureProfile $measureProfile = null) {
        $checkpoint = is_null($objective) && is_null($measureProfile);
        if ($checkpoint) {
            throw new ServiceException("An objective or measure profile should be defined.");
        }

        if (!is_null($objective)) {
            $this->daoSource->unlinkObjective($initiative, $objective);
        }

        if (!is_null($measureProfile)) {
            $this->daoSource->unlinkLeadMeasure($initiative, $measureProfile);
        }
    }

    public function addPhase(Phase $phase, Initiative $initiative) {
        $phases = $this->phaseDaoSource->listPhases($initiative);
        foreach ($phases as $data) {
            if ($phase->phaseNumber == $data->phaseNumber) {
                throw new ServiceException("Phase already defined. Please use the update facility instead");
            }
        }
        $this->phaseDaoSource->addPhase($phase, $initiative);
    }

    public function getPhase($id = null, Component $component = null) {
        if (!is_null($id)) {
            return $this->phaseDaoSource->getPhaseByIdentifier($id);
        } else if (!is_null($component)) {
            return $this->phaseDaoSource->getPhaseByComponent($component);
        }
    }

    public function updatePhase(Phase $phase) {
        $initiative = $this->daoSource->getInitiativeByPhase($phase);
        $phaseList = $this->phaseDaoSource->listPhases($initiative);
        foreach ($phaseList as $data) {
            if ($data->title == $phase->title && $data->id != $phase->id) {
                throw new ServiceException("Phase not updated.");
            }
        }
        $this->phaseDaoSource->updatePhase($phase);
    }

    public function deletePhase($id) {
        $this->phaseDaoSource->deletePhase($id);
    }

    public function manageComponent(Component $component, Phase $phase) {
        $components = $this->componentDaoSource->listComponents($phase);
        foreach ($components as $data) {
            if (strcasecmp($data->description, $component->description) == 0) {
                throw new ServiceException("Component already defined");
            }
        }
        if ($component->isNew()) {
            $this->componentDaoSource->addComponent($component, $phase);
        } else {
            $this->componentDaoSource->updateComponent($component, $phase);
        }
    }

    public function getComponent($id = null, Activity $activity = null) {
        if (!is_null($id)) {
            return $this->componentDaoSource->getComponentByIdentifier($id);
        } elseif (!is_null($activity)) {
            return $this->componentDaoSource->getComponentByActivity($activity);
        }
    }

    public function deleteComponent($id) {
        $this->componentDaoSource->deleteComponent($id);
    }

    public function addActivity(Activity $activity, Component $component) {
        $activities = $this->activityDaoSource->listActivities($component);
        foreach ($activities as $data) {
            if (strcasecmp($activity->title, $data->title) == 0 && $activity->startingPeriod->format('Y-m-d') == $data->startingPeriod->format('Y-m-d') && $activity->endingPeriod->format('Y-m-d') == $data->endingPeriod->format('Y-m-d')) {
                throw new ServiceException("Activity already defined. Please use the update facility instead");
            }
        }
        $this->activityDaoSource->addActivity($activity, $component);
    }

    public function updateActivity(Activity $activity, Component $component) {
        $activities = $this->activityDaoSource->listActivities($component);
        foreach ($activities as $data) {
            if ($activity->id != $data->id && strcasecmp($activity->title, $data->title) == 0 && $activity->startingPeriod->format('Y-m-d') == $data->startingPeriod->format('Y-m-d') && $activity->endingPeriod->format('Y-m-d') == $data->endingPeriod->format('Y-m-d')) {
                throw new ServiceException("Activity already defined. Update facility denied");
            }
        }
        $this->activityDaoSource->updateActivity($activity, $component);
    }

    public function deleteActivity($id) {
        $this->activityDaoSource->deleteActivity($id);
    }

    public function getActivity($id) {
        return $this->activityDaoSource->getActivity($id);
    }

    public function insertActivityMovement(Activity $activity) {
        $this->movementDaoSource->recordMovements($activity);
    }

}
