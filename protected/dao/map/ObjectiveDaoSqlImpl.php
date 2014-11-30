<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\map\ObjectiveDao;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;

/**
 * Description of ObjectiveDaoSqlImpl
 *
 * @author britech
 */
class ObjectiveDaoSqlImpl implements ObjectiveDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function listAllObjectives() {
        
    }

    public function listObjectivesByStrategyMap(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT obj_id, pers_desc, theme_desc, obj_desc FROM smap_objectives t1 '
                    . 'JOIN smap_perspectives ON pers_ref = pers_id '
                    . 'LEFT JOIN smap_themes ON theme_ref = theme_id '
                    . 'WHERE t1.map_ref=:ref '
                    . 'ORDER BY pos_order ASC, obj_desc ASC');
            $dbst->execute(array('ref' => $strategyMap->id));

            $objectives = array();
            while ($data = $dbst->fetch()) {
                $objective = new Objective();
                $objective->perspective = new Perspective();
                $objective->theme = new Theme();
                list($objective->id, $objective->perspective->description, $objective->theme->description, $objective->description) = $data;
                array_push($objectives, $objective);
            }
            return $objectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateObjectivesCoveragePeriodsByStrategyMap(StrategyMap $strategyMap) {
        
    }

}
