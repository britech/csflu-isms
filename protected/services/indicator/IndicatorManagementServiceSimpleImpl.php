<?php

namespace org\csflu\isms\service\indicator;

use org\csflu\isms\service\indicator\IndicatorManagementService;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\dao\indicator\IndicatorDaoSqlImpl as IndicatorDao;
use org\csflu\isms\dao\indicator\BaselineDaoSqlImpl as BaselineDao;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;

/**
 * Description of IndicatorManagementServiceSimpleImpl
 *
 * @author britech
 */
class IndicatorManagementServiceSimpleImpl implements IndicatorManagementService {

    private $daoSource;
    private $baselineDaoSource;
    private $logger;

    public function __construct() {
        $this->daoSource = new IndicatorDao();
        $this->baselineDaoSource = new BaselineDao();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listIndicators() {
        return $this->daoSource->listIndicators();
    }

    public function retrieveIndicator($id = null, Baseline $baseline = null) {
        if (!is_null($id) || !empty($id)) {
            return $this->daoSource->retrieveIndicator($id);
        }

        if (!is_null($baseline)) {
            return $this->daoSource->retrieveIndicatorByBaseline($baseline);
        }
    }

    public function updateBaseline(Baseline $baseline) {
        $indicator = $this->daoSource->retrieveIndicatorByBaseline($baseline);

        $match = false;
        foreach ($indicator->baselineData as $data) {
            if ($data->baselineDataGroup == $baseline->baselineDataGroup && $data->coveredYear == $baseline->coveredYear) {
                $match = true;
                break;
            }
        }

        if ($match) {
            $this->logger->warn("Baseline arguments already defined\nArguments: " . $baseline);
        } else {
            $this->baselineDaoSource->updateBaseline($baseline);
        }
    }

    public function unlinkBaseline($id) {
        $this->baselineDaoSource->deleteBaseline($id);
    }

    public function manageIndicator(Indicator $indicator) {
        $indicators = $this->daoSource->listIndicators();

        $match = false;
        foreach ($indicators as $data) {
            if ($data->description == $indicator->description && is_null($indicator->id)) {
                $match = true;
                break;
            }
        }

        if ($match) {
            throw new ServiceException("Indicator already defined. Please use the update facility instead.");
        }

        if (!is_null($indicator->id)) {
            $this->daoSource->updateIndicator($indicator);
        } else {
            return $this->daoSource->enlistIndicator($indicator);
        }
    }

    public function addBaseline(Baseline $baseline, Indicator $indicator) {
        $baselineData = $this->daoSource->retrieveIndicatorBaselineList($indicator);
        $found = false;
        foreach ($baselineData as $data) {
            if ($data->coveredYear == $baseline->coveredYear && $data->baselineDataGroup == $baseline->baselineDataGroup) {
                $found = true;
                break;
            }
        }
        if ($found) {
            throw new ServiceException('Baseline already added, please use the update facility instead');
        } else {
            $this->baselineDaoSource->enlistBaseline($baseline, $indicator);
        }
    }

    public function getBaseline($id) {
        return $this->baselineDaoSource->getBaseline($id);
    }

}
