<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementService;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\dao\ubt\UnitBreakthroughDaoSqlImpl as UnitBreakthroughDao;
use org\csflu\isms\dao\ubt\LeadMeasureDaoSqlImpl as LeadMeasureDao;
use org\csflu\isms\dao\ubt\WigSessionDaoSqlmpl as WigSessionDao;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl as ObjectiveDao;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;

/**
 *
 * @author britech
 */
class UnitBreakthroughManagementServiceSimpleImpl implements UnitBreakthroughManagementService {

    private $daoSource;
    private $leadMeasureDaoSource;
    private $wigSessionDaoSource;
    private $objectiveDaoSource;
    private $measureProfileDaoSource;

    public function __construct() {
        $this->daoSource = new UnitBreakthroughDao();
        $this->leadMeasureDaoSource = new LeadMeasureDao();
        $this->wigSessionDaoSource = new WigSessionDao();
        $this->objectiveDaoSource = new ObjectiveDao();
        $this->measureProfileDaoSource = new MeasureProfileDao();
    }

    public function getUnitBreakthrough($id = null, LeadMeasure $leadMeasure = null, WigSession $wigSession = null) {
        if (!is_null($id) && !empty($id)) {
            return $this->daoSource->getUnitBreakthroughByIdentifier($id);
        } elseif (!is_null($leadMeasure)) {
            return $this->daoSource->getUnitBreakthroughByLeadMeasure($leadMeasure);
        } elseif (!is_null($wigSession)) {
            return $this->daoSource->getUnitBreakthroughByWigSession($wigSession);
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

    public function listUnitBreakthrough(StrategyMap $strategyMap = null, Department $department = null) {
        if (!is_null($strategyMap)) {
            return $this->daoSource->listUnitBreakthroughByStrategyMap($strategyMap);
        } elseif (!is_null($department)) {
            return $this->daoSource->listUnitBreakthroughByDepartment($department);
        }
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

        if (count($unitBreakthrough->objectives) == 0 && count($unitBreakthrough->measures) == 0) {
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

    public function deleteAlignments(UnitBreakthrough $unitBreakthrough, Objective $objective = null, MeasureProfile $measureProfile = null) {
        if (is_null($objective) && is_null($measureProfile)) {
            throw new ServiceException("An Objective or Measure should be selected to execute this service");
        }

        if (!is_null($objective)) {
            $this->daoSource->unlinkObjective($unitBreakthrough, $objective);
        }

        if (!is_null($measureProfile)) {
            $this->daoSource->unlinkMeasureProfile($unitBreakthrough, $measureProfile);
        }
    }

    public function insertWigSession(WigSession $wigSession, UnitBreakthrough $unitBreakthrough) {
        $wigSessions = $this->wigSessionDaoSource->listWigSessions($unitBreakthrough);
        $startDate = $wigSession->startingPeriod->format('Y-m-d');
        $endDate = $wigSession->endingPeriod->format('Y-m-d');
        foreach ($wigSessions as $data) {
            if ($data->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN) {
                throw new ServiceException("A WIG Session is in progress. Please close the ongoing WIG Session to continue");
            }

            if ($startDate == $data->startingPeriod->format('Y-m-d') and $endDate == $data->endingPeriod->format('Y-m-d')) {
                throw new ServiceException("A WIG Session with the given timeline is already defined. Please try again.");
            }
        }
        return $this->wigSessionDaoSource->insertWigSession($wigSession, $unitBreakthrough);
    }

    public function getWigSessionData($id) {
        return $this->wigSessionDaoSource->getWigSessionData($id);
    }

    public function updateWigSession(WigSession $wigSession) {
        $unitBreakthrough = $this->daoSource->getUnitBreakthroughByWigSession($wigSession);
        $wigSessions = $this->wigSessionDaoSource->listWigSessions($unitBreakthrough);
        $startDate = $wigSession->startingPeriod->format('Y-m-d');
        $endDate = $wigSession->endingPeriod->format('Y-m-d');
        foreach ($wigSessions as $data) {
            if ($data->id != $wigSession->id && ($startDate == $data->startingPeriod->format('Y-m-d') && $endDate == $data->endingPeriod->format('Y-m-d'))) {
                throw new ServiceException("A WIG Session with the given timeline is already defined. Please try again.");
            }
        }
        $this->wigSessionDaoSource->updateWigSession($wigSession);
    }

    public function deleteWigSession($id) {
        $this->wigSessionDaoSource->deleteWigSession($id);
    }

}
