<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementService;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\dao\ubt\UnitBreakthroughDaoSqlImpl as UnitBreakthroughDao;
use org\csflu\isms\dao\ubt\LeadMeasureDaoSqlImpl as LeadMeasureDao;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl as ObjectiveDao;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;

/**
 *
 * @author britech
 */
class UnitBreakthroughManagementServiceSimpleImpl implements UnitBreakthroughManagementService {

    private $daoSource;
    private $leadMeasureDaoSource;
    private $objectiveDaoSource;
    private $measureProfileDaoSource;

    public function __construct() {
        $this->daoSource = new UnitBreakthroughDao();
        $this->leadMeasureDaoSource = new LeadMeasureDao();
        $this->objectiveDaoSource = new ObjectiveDao();
        $this->measureProfileDaoSource = new MeasureProfileDao();
    }

    public function getUnitBreakthrough($id = null, LeadMeasure $leadMeasure = null) {
        if (!is_null($id) && !empty($id)) {
            return $this->daoSource->getUnitBreakthroughByIdentifier($id);
        } elseif (!is_null($leadMeasure)) {
            return $this->daoSource->getUnitBreakthroughByLeadMeasure($leadMeasure);
        }
    }

    public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap) {
        $unitBreakthroughs = $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);

        foreach ($unitBreakthroughs as $data) {
            if ($unitBreakthrough->unit->id == $data->unit->id && $unitBreakthrough->description == $data->description) {
                throw new ServiceException("UnitBreakthrough already defined. Please use the update facility instead");
            }
        }
        return $this->daoSource->insertUnitBreakthrough($unitBreakthrough, $strategyMap);
    }

    public function listUnitBreakthrough(StrategyMap $strategyMap) {
        return $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);
    }

    public function updateUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap) {
        $unitBreakthroughs = $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);

        foreach ($unitBreakthroughs as $data) {
            if ($data->id != $unitBreakthrough->id && $unitBreakthrough->unit->id == $data->unit->id && $unitBreakthrough->description == $data->description) {
                throw new ServiceException("UnitBreakthrough already defined. Please use the update facility instead");
            }
        }
        $this->daoSource->updateUnitBreakthrough($unitBreakthrough);
    }

    public function insertLeadMeasures(UnitBreakthrough $unitBreakthrough) {
        $leadMeasures = $this->leadMeasureDaoSource->listLeadMeasures($unitBreakthrough);
        $count = 0;
        foreach ($leadMeasures as $leadMeasure) {
            if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE) {
                $count++;
            }
        }

        if ($count < 2) {
            $unitBreakthrough->leadMeasures = $this->validateLeadMeasures($unitBreakthrough, $leadMeasures);
            $this->leadMeasureDaoSource->insertLeadMeasures($unitBreakthrough);
            return $unitBreakthrough->leadMeasures;
        } else {
            throw new ServiceException("Active Lead Measures already defined in the Unit Breakthrough. Please use the update or management facility.");
        }
    }

    private function validateLeadMeasures(UnitBreakthrough $unitBreakthroughInput, $leadMeasures) {
        $acceptedLeadMeasures = array();
        foreach ($unitBreakthroughInput->leadMeasures as $leadMeasure) {
            foreach ($leadMeasures as $data) {
                if ($leadMeasure->description != $data->description) {
                    array_push($acceptedLeadMeasures, $leadMeasure);
                }
            }
        }
        if (count($acceptedLeadMeasures) == 0) {
            throw new ServiceException("No Lead Measures inserted");
        }
        return $acceptedLeadMeasures;
    }

    public function retrieveLeadMeasure($id) {
        return $this->leadMeasureDaoSource->getLeadMeasure($id);
    }

    public function addAlignments(UnitBreakthrough $unitBreakthrough) {
        $unitBreakthrough->objectives = $this->filterObjectives($unitBreakthrough);
        $unitBreakthrough->measures = $this->filterMeasureProfiles($unitBreakthrough);

        if (count($unitBreakthrough->objectives) > 0) {
            $this->daoSource->linkObjectives($unitBreakthrough);
        }

        if (count($unitBreakthrough->measures) > 0) {
            $this->daoSource->linkMeasureProfiles($unitBreakthrough);
        }
        
        if(count($unitBreakthrough->objectives) == 0 && count($unitBreakthrough->measures) == 0){
            throw new ServiceException("No alignments added");
        }
        
        return $unitBreakthrough;
    }

    private function filterObjectives(UnitBreakthrough $unitBreakthrough) {
        $linkedObjectives = $this->daoSource->listObjectives($unitBreakthrough);
        $objectivesToLink = array();
        foreach ($unitBreakthrough->objectives as $objective) {
            $found = false;
            foreach ($linkedObjectives as $data) {
                if ($data->id == $objective->id) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                array_push($objectivesToLink, $this->objectiveDaoSource->getObjective($objective->id));
            }
        }
        return $objectivesToLink;
    }

    private function filterMeasureProfiles(UnitBreakthrough $unitBreakthrough) {
        $linkedMeasureProfiles = $this->daoSource->listMeasureProfiles($unitBreakthrough);
        $measureProfilesToLink = array();
        foreach ($unitBreakthrough->measures as $measure) {
            $found = false;
            foreach ($linkedMeasureProfiles as $data) {
                if ($data->id == $measure->id) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                array_push($measureProfilesToLink, $this->measureProfileDaoSource->getMeasureProfile($measure->id));
            }
        }
        return $measureProfilesToLink;
    }

}
