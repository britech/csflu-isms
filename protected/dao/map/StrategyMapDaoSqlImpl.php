<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\dao\map\StrategyMapDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;

/**
 *
 * @author britech
 */
class StrategyMapDaoSqlImpl implements StrategyMapDao {

    private $db;
    
    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }
    
    public function listStrategyMaps() {
        try {
            $dbst = $this->db->prepare('SELECT map_id, map_desc, map_vision, map_type, map_stat FROM smap_main ORDER BY map_stat ASC, period_date_end DESC');
            $dbst->execute();

            $maps = array();

            while ($data = $dbst->fetch()) {
                $map = new StrategyMap();
                list($map->id,
                        $map->name,
                        $map->visionStatement,
                        $map->strategyType,
                        $map->strategyEnvironmentStatus) = $data;
                array_push($maps, $map);
            }

            return $maps;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insert($strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO smap_main(map_desc, map_vision, map_mission, map_values, map_type, period_date_start, period_date_end) '
                    . 'VALUES(:description, :vision, :mission, :values, :type, :start, :end)');
            $dbst->execute(array('description' => $strategyMap->name,
                'vision' => $strategyMap->visionStatement,
                'mission' => $strategyMap->missionStatement,
                'values' => $strategyMap->valuesStatement,
                'type' => $strategyMap->strategyType,
                'start' => $strategyMap->startingPeriodDate->format('Y-m-d'),
                'end' => $strategyMap->endingPeriodDate->format('Y-m-d')));

            $id = $this->db->lastInsertId();
            $this->db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getStrategyMap($id) {
        try {
            $dbst = $this->db->prepare('SELECT map_id, map_desc, map_vision, map_mission, map_values, map_type, period_date_start, period_date_end, map_stat FROM smap_main WHERE map_id=:id');
            $dbst->execute(array('id'=>$id));
            
            $map = new StrategyMap();
            while($data = $dbst->fetch()){
                list($map->id,
                        $map->name,
                        $map->visionStatement,
                        $map->missionStatement,
                        $map->valuesStatement,
                        $map->strategyType,
                        $map->startingPeriodDate,
                        $map->endingPeriodDate,
                        $map->strategyEnvironmentStatus) = $data;
            }
            return $map;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getStrategyMapByPerspective($perspective) {
        try{
            $dbst = $this->db->prepare('SELECT map_ref FROM smap_perspectives WHERE pers_id=:id');
            $dbst->execute(array('id'=>$perspective->id));
            
            while($data = $dbst->fetch()){
                list($map) = $data;
            }
            return $this->getStrategyMap($map);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
