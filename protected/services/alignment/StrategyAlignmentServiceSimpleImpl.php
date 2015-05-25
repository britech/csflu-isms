<?php

namespace org\csflu\isms\service\alignment;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl;
use org\csflu\isms\dao\ubt\UnitBreakthroughDaoSqlImpl;

/**
 * Description of StrategyAlignmentServiceSimpleImpl
 *
 * @author britech
 */
class StrategyAlignmentServiceSimpleImpl implements StrategyAlignmentService {

    private $initiativeDao;
    private $ubtDao;

    public function __construct() {
        $this->initiativeDao = new InitiativeDaoSqlImpl();
        $this->ubtDao = new UnitBreakthroughDaoSqlImpl();
    }

    public function listAlignedInitiatives(StrategyMap $strategyMap, Objective $objective = null, MeasureProfile $measureProfile = null) {
        $initiatives = $this->initiativeDao->listInitiativesByStrategyMap($strategyMap);
        $alignedInitiatives = array();

        foreach ($initiatives as $initiative) {
            if ((!is_null($objective) && $this->isInitiativeAligned($initiative, $objective)) || (!is_null($measureProfile) && $this->isInitiativeAligned($initiative, null, $measureProfile))) {
                $alignedInitiatives = array_merge($alignedInitiatives, array($initiative));
            }
        }

        return $alignedInitiatives;
    }

    private function isInitiativeAligned(Initiative $initiative, Objective $objective = null, MeasureProfile $measureProfile = null) {
        $result = false;
        if (!is_null($objective)) {
            foreach ($initiative->objectives as $data) {
                if ($objective->id == $data->id) {
                    return true;
                }
            }
        } elseif (!is_null($measureProfile)) {
            foreach ($initiative->leadMeasures as $leadMeasure) {
                if ($leadMeasure->id == $measureProfile->id) {
                    return true;
                }
            }
        }
        return $result;
    }

    public function listAlignedUnitBreakthroughs(StrategyMap $strategyMap, Objective $objective = null, MeasureProfile $measureProfile = null) {
        $unitBreakthroughs = $this->ubtDao->listUnitBreakthroughByStrategyMap($strategyMap);
        $alignedUnitBreakthroughs = array();

        foreach ($unitBreakthroughs as $unitBreakthrough) {
            if ((!is_null($objective) && $this->isUnitBreakthroughAligned($unitBreakthrough, $objective)) || (!is_null($measureProfile) && $this->isUnitBreakthroughAligned($unitBreakthrough, null, $measureProfile))) {
                $alignedUnitBreakthroughs = array_merge($alignedUnitBreakthroughs, array($unitBreakthrough));
            }
        }

        return $alignedUnitBreakthroughs;
    }

    private function isUnitBreakthroughAligned(UnitBreakthrough $unitBreakthrough, Objective $objective = null, MeasureProfile $measureProfile = null) {
        $result = false;
        if (!is_null($objective)) {
            foreach ($unitBreakthrough->objectives as $data) {
                if ($objective->id == $data->id) {
                    return true;
                }
            }
        } elseif (!is_null($measureProfile)) {
            foreach ($unitBreakthrough->measures as $measure) {
                if ($measureProfile->id == $measure->id) {
                    return true;
                }
            }
        }
        return $result;
    }

}
