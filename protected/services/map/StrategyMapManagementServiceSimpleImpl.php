<?php

namespace org\csflu\isms\service\map;

use org\csflu\isms\service\map\StrategyMapManagementService;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;
use org\csflu\isms\dao\map\PerspectiveDaoSqlImpl as PerspectiveDao;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;
use org\csflu\isms\models\map\Objective;

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

    public function insert(StrategyMap $strategyMap) {
        return $this->mapDaoSource->insert($strategyMap);
    }

    public function getStrategyMap($id = null, Perspective $perspective = null, Objective $objective = null, Theme $theme = null) {
        if (!is_null($id) && !empty($id)) {
            return $this->mapDaoSource->getStrategyMap($id);
        }

        if (!is_null($perspective) && !empty($perspective)) {
            return $this->mapDaoSource->getStrategyMapByPerspective($perspective);
        }

        if (!is_null($theme) && !empty($theme)) {
            return $this->mapDaoSource->getStrategyMapByTheme($theme);
        }
    }

    public function insertPerspective(Perspective $perspective, StrategyMap $strategyMap) {
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

    public function listPerspectives(StrategyMap $strategyMap = null) {

        if (is_null($strategyMap)) {
            return $this->perspectiveDaoSource->listAllPerspectives();
        } else {
            return $this->perspectiveDaoSource->listPerspectivesByStrategyMap($strategyMap);
        }
    }

    public function getPerspective($id) {
        return $this->perspectiveDaoSource->getPerspective($id);
    }

    public function updatePerspective(Perspective $perspective) {
        $strategyMap = $this->mapDaoSource->getStrategyMapByPerspective($perspective);

        $perspectives = $this->perspectiveDaoSource->listPerspectivesByStrategyMap($strategyMap);
        $match = false;
        foreach ($perspectives as $perspectiveObject) {
            if ($perspective->description == $perspectiveObject->description && $perspective->positionOrder != $perspectiveObject->positionOrder) {
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

    public function update(StrategyMap $strategyMap) {
        $this->mapDaoSource->update($strategyMap);
    }

    public function listThemes(StrategyMap $strategyMap = null) {
        if (is_null($strategyMap)) {
            return $this->perspectiveDaoSource->listAllThemes();
        } else {
            return $this->perspectiveDaoSource->listThemesByStrategyMap($strategyMap);
        }
    }

    public function deleteTheme($id) {
        $this->perspectiveDaoSource->deleteTheme($id);
    }

    public function manageTheme(Theme $theme, StrategyMap $strategyMap = null) {
        $themes = $this->listThemes($strategyMap);

        $match = false;
        foreach ($themes as $themeObject) {
            if ($theme->description == $themeObject->description) {
                $match = true;
                break;
            }
        }

        if ($match) {
            throw new ServiceException('Theme already defined. Please try again.');
        } else {
            if (!is_null($strategyMap) || !empty($strategyMap)) {
                $this->perspectiveDaoSource->insertTheme($theme, $strategyMap);
            } else {
                $this->perspectiveDaoSource->updateTheme($theme);
            }
        }
    }

    public function getTheme($id) {
        return $this->perspectiveDaoSource->getTheme($id);
    }

}
