<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\service\map\StrategyMapManagementService;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;
use org\csflu\isms\dao\map\PerspectiveDaoSqlImpl as PerspectiveDao;
use org\csflu\isms\exceptions\ServiceException;

/**
 *
 *
 * @author britech
 */
class StrategyMapManagementServiceSimpleImpl implements StrategyMapManagementService {

    private $mapDaoSource;
    private $perspectiveDaoSource;

    public function __construct() {
        $this->mapDaoSource = new StrategyMapDao();
        $this->perspectiveDaoSource = new PerspectiveDao();
    }

    public function listStrategyMaps() {
        return $this->mapDaoSource->listStrategyMaps();
    }

    public function insert($strategyMap) {
        return $this->mapDaoSource->insert($strategyMap);
    }

    public function getStrategyMap($id = null, $perspective = null, $objective = null, $theme = null) {
        if (!is_null($id) && !empty($id)) {
            return $this->mapDaoSource->getStrategyMap($id);
        }

        if (!is_null($perspective) && !empty($perspective)) {
            return $this->mapDaoSource->getStrategyMapByPerspective($perspective);
        }
    }

    public function insertPerspective($perspective, $strategyMap) {
        $perspectiveList = $this->listPerspectives($strategyMap);
        $match = false;
        $identicalDescription = false;
        foreach ($perspectiveList as $perspectiveObject) {
            if ($perspective->positionOrder == $perspectiveObject->positionOrder) {
                $match = true;
                break;
            }

            if ($perspectiveObject->description == $perspective->description) {
                $identicalDescription = true;
                break;
            }
        }
        if ($match) {
            throw new ServiceException('Position Order already in-use. Please try again.');
        }

        if ($identicalDescription) {
            throw new ServiceException('Perspective already defined. Please try again.');
        }
        $this->perspectiveDaoSource->insertPerspective($perspective, $strategyMap);
    }

    public function listPerspectives($strategyMap = null) {

        if (is_null($strategyMap)) {
            return $this->perspectiveDaoSource->listAllPerspectives();
        } else {
            return $this->perspectiveDaoSource->listPerspectivesByStrategyMap($strategyMap);
        }
    }

    public function getPerspective($id) {
        return $this->perspectiveDaoSource->getPerspective($id);
    }

    public function updatePerspective($perspective) {
        $strategyMap = $this->mapDaoSource->getStrategyMapByPerspective($perspective);

        $perspectives = $this->perspectiveDaoSource->listPerspectivesByStrategyMap($strategyMap);
        $match = false;
        foreach ($perspectives as $perspectiveObject) {
            if($perspective->description == $perspectiveObject->description && $perspective->positionOrder != $perspectiveObject->positionOrder){
                $match = true;
                break;
            }
        }

        if ($match) {
            throw new ServiceException('Perspective already defined. Please try again.');
        } else {
            $this->perspectiveDaoSource->updatePerspective($perspective);
        }
    }

    public function deletePerspective($id) {
        $this->perspectiveDaoSource->deletePerspective($id);
    }

    public function update($strategyMap) {
        $this->mapDaoSource->update($strategyMap);
    }

}
