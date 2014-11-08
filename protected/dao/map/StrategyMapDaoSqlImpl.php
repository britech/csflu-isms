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

    public function listStrategyMaps() {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT map_id, map_desc, map_vision, map_type, map_stat FROM smap_main ORDER BY map_stat ASC, period_date_end DESC');
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
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO smap_main(map_desc, map_vision, map_mission, map_values, map_type, period_date_start, period_date_end) '
                    . 'VALUES(:description, :vision, :mission, :values, :type, :start, :end)');
            $dbst->execute(array('description' => $strategyMap->name,
                'vision' => $strategyMap->visionStatement,
                'mission' => $strategyMap->missionStatement,
                'values' => $strategyMap->valuesStatement,
                'type' => $strategyMap->strategyType,
                'start' => $strategyMap->startingPeriodDate->format('Y-m-d'),
                'end' => $strategyMap->endingPeriodDate->format('Y-m-d')));

            $id = $db->lastInsertId();
            $db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
