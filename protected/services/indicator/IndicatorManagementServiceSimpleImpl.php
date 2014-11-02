<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\service\indicator\IndicatorManagementService;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\dao\indicator\IndicatorDaoSqlImpl as IndicatorDao;
use org\csflu\isms\dao\indicator\BaselineDaoSqlImpl as BaselineDao;

/**
 * Description of IndicatorManagementServiceSimpleImpl
 *
 * @author britech
 */
class IndicatorManagementServiceSimpleImpl implements IndicatorManagementService {

    private $daoSource;
    private $baselineDaoSource;

    public function __construct() {
        $this->daoSource = new IndicatorDao();
        $this->baselineDaoSource = new BaselineDao();
    }

    public function listIndicators() {
        return $this->daoSource->listIndicators();
    }

    public function enlistIndicator($indicator) {
        return $this->daoSource->enlistIndicator($indicator);
    }

    public function retrieveIndicator($id) {
        return $this->daoSource->retrieveIndicator($id);
    }

    public function updateIndicator($indicator) {
        $this->daoSource->updateIndicator($indicator);
    }

    public function addBaselineDataToIndicator($indicator) {
        $baselineData = $this->daoSource->retrieveIndicatorBaselineList($indicator);
        $found = false;
        foreach ($baselineData as $baseline) {
            if ($baseline->coveredYear == $indicator->baselineData->coveredYear && $baseline->baselineDataGroup == $indicator->baselineData->baselineDataGroup) {
                $found = true;
                break;
            }
        }
        if ($found) {
            throw new ServiceException('Baseline already added, please use the update facility instead');
        } else {
            $this->baselineDaoSource->enlistBaseline($indicator);
        }
    }

    public function getBaselineDataFromIndicator($indicator, $id) {
        $baselineData = $this->daoSource->retrieveIndicatorBaselineList($indicator);

        foreach ($baselineData as $baselineData) {
            if ($baselineData->id == $id) {
                return $baselineData;
            }
        }
    }

    public function updateBaseline($baseline) {
        $this->baselineDaoSource->updateBaseline($baseline);
    }

    public function unlinkBaseline($id) {
        $this->baselineDaoSource->deleteBaseline($id);
    }

}
