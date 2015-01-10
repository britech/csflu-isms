<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\service\indicator\ScorecardManagementService;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\LeadOffice;

/**
 * Description of ScorecardManagementServiceSimpleImpl
 *
 * @author britech
 */
class ScorecardManagementServiceSimpleImpl implements ScorecardManagementService {

    private $daoSource;
    private $mapDaoSource;
    private $logger;

    public function __construct() {
        $this->daoSource = new MeasureProfileDao();
        $this->mapDaoSource = new StrategyMapDao();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMeasureProfiles(StrategyMap $strategyMap) {
        return $this->daoSource->listMeasureProfiles($strategyMap);
    }

    public function insertMeasureProfile(MeasureProfile $measureProfile, StrategyMap $strategyMap) {
        $leadMeasures = $this->listMeasureProfiles($strategyMap);

        foreach ($leadMeasures as $leadMeasure) {
            if ($leadMeasure->objective->id == $measureProfile->objective->id && $leadMeasure->indicator->id == $measureProfile->indicator->id) {
                throw new ServiceException("Measure Profile already defined. Please use the update facility instead");
            }
        }
        return $this->daoSource->insertMeasureProfile($measureProfile);
    }

    public function getMeasureProfile($id = null, LeadOffice $leadOffice = null) {
        if (!is_null($id)) {
            return $this->daoSource->getMeasureProfile($id);
        } elseif (!is_null($leadOffice)) {
            return $this->daoSource->getMeasureProfileByLeadOffice($leadOffice);
        }
    }

    public function insertLeadOffices(MeasureProfile $measureProfile) {
        $leadOffices = $this->daoSource->listLeadOffices($measureProfile);

        $finalLeadOffices = array();
        foreach ($measureProfile->leadOffices as $leadOffice) {
            $match = false;
            foreach ($leadOffices as $checker) {
                if ($leadOffice->department->id == $checker->department->id) {
                    $match = true;
                    break;
                }
            }

            if (!$match) {
                array_push($finalLeadOffices, $leadOffice);
            } else {
                $this->logger->warn("Parameters already defined. Please use the update facility instead\n{$leadOffice}");
            }

            $finalMeasureProfile = clone $measureProfile;
            $finalMeasureProfile->leadOffices = $finalLeadOffices;
        }
        if (count($finalMeasureProfile->leadOffices) > 0) {
            $this->daoSource->insertLeadOffices($finalMeasureProfile);
        } else {
            throw new ServiceException("No Lead Offices enlisted");
        }
    }

    public function insertTargets(MeasureProfile $measureProfile) {
        $targets = $this->daoSource->listTargets($measureProfile);

        $finalTargets = array();
        foreach ($measureProfile->targets as $target) {
            $match = false;
            foreach ($targets as $checker) {
                if ($target->dataGroup == $checker->dataGroup && $target->coveredYear == $checker->coveredYear) {
                    $match = true;
                    break;
                }
            }

            if (!$match) {
                array_push($finalTargets, $target);
            } else {
                $this->logger->warn("Parameters already defined. Please use the update facility instead\n{$target}");
            }
        }

        $measureProfile->targets = $finalTargets;

        if (count($measureProfile->targets) > 0) {
            $this->daoSource->insertTargets($measureProfile);
        } else {
            throw new ServiceException("No Target Data enlisted");
        }
    }

    public function updateMeasureProfile(MeasureProfile $measureProfile) {
        $strategyMap = $this->mapDaoSource->getStrategyMapByObjective($measureProfile->objective);
        $leadMeasures = $this->listMeasureProfiles($strategyMap);

        foreach ($leadMeasures as $leadMeasure) {
            if ($measureProfile->id != $leadMeasure->id && $leadMeasure->objective->id == $measureProfile->objective->id && $leadMeasure->indicator->id == $measureProfile->indicator->id) {
                throw new ServiceException("Measure Profile already defined");
            }
        }
        $this->daoSource->updateMeasureProfile($measureProfile);
    }

    public function getLeadOffice(MeasureProfile $measureProfile, $id) {
        $leadOffices = $this->daoSource->listLeadOffices($measureProfile);

        foreach ($leadOffices as $leadOffice) {
            if ($leadOffice->id == $id) {
                return $leadOffice;
            }
        }
    }

    public function updateLeadOffice(LeadOffice $leadOffice) {
        $this->daoSource->updateLeadOffice($leadOffice);
    }

    public function deleteLeadOffice($id) {
        $this->daoSource->deleteLeadOffice($id);
    }

}
