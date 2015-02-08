<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\initiative\InitiativeManagementService;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl as InitiativeDao;
use org\csflu\isms\dao\initiative\PhaseDaoSqlImpl as PhaseDao;
use org\csflu\isms\dao\initiative\ComponentDaoSqlImpl as ComponentDao;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl as ObjectiveDao;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;

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
    private $mapDaoSource;
    private $objectiveDaoSource;
    private $mpDaoSource;

    public function __construct() {
        $this->daoSource = new InitiativeDao();
        $this->phaseDaoSource = new PhaseDao();
        $this->componentDaoSource = new ComponentDao();
        $this->mapDaoSource = new StrategyMapDao();
        $this->objectiveDaoSource = new ObjectiveDao();
        $this->mpDaoSource = new MeasureProfileDao();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        return $this->daoSource->listInitiatives($strategyMap);
    }

    public function addInitiative(Initiative $initiative, StrategyMap $strategyMap) {
        $initiatives = $this->daoSource->listInitiatives($strategyMap);

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
        $initiatives = $this->daoSource->listInitiatives($strategyMap);

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

    public function getPhase($id, Initiative $initiative) {
        $phases = $this->phaseDaoSource->listPhases($initiative);
        foreach ($phases as $data) {
            if ($id == $data->id) {
                $phase = new Phase();
                $phase->id = $data->id;
                $phase->phaseNumber = $data->phaseNumber;
                $phase->title = $data->title;
                $phase->description = $data->description;
                return $phase;
            }
        }
        return null;
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

    public function addComponent(Component $component, Phase $phase) {
        $components = $this->componentDaoSource->listComponents($phase);
        foreach ($components as $data) {
            if (strcasecmp($data->description, $component->description) == 0) {
                throw new ServiceException("Component already defined");
            }
        }
        $this->componentDaoSource->addComponent($component, $phase);
    }

    public function getComponent($id, Phase $phase) {
        $components = $this->componentDaoSource->listComponents($phase);
        foreach ($components as $data) {
            if ($id == $data->id) {
                return new Component($data->description, $data->id);
            }
        }
    }

}
