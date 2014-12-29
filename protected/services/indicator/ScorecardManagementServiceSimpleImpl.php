<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\service\indicator\ScorecardManagementService;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;

/**
 * Description of ScorecardManagementServiceSimpleImpl
 *
 * @author britech
 */
class ScorecardManagementServiceSimpleImpl implements ScorecardManagementService {

    private $daoSource;
    private $logger;

    public function __construct() {
        $this->daoSource = new MeasureProfileDao();
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

    public function getMeasureProfile($id) {
        return $this->daoSource->getMeasureProfile($id);
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
        
    }

}
