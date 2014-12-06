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
        try {
            $dbst = $this->db->prepare('SELECT DISTINCT(obj_desc) FROM smap_objectives ORDER BY obj_desc ASC');
            $objectives = array();            
            
            while($data = $dbst->execute()){
                $objective = new Objective();
                list($objective->description) = $data;
                array_push($objectives, $objective);
            }
            
            return $objectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listObjectivesByStrategyMap(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT obj_id, pers_desc, theme_desc, obj_desc, pers_id, theme_id, pos_order FROM smap_objectives t1 '
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
                list($objective->id,
                        $objective->perspective->description,
                        $objective->theme->description,
                        $objective->description,
                        $objective->perspective->id,
                        $objective->theme->id,
                        $objective->perspective->positionOrder) = $data;
                array_push($objectives, $objective);
            }
            return $objectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateObjectivesCoveragePeriodsByStrategyMap(StrategyMap $strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE smap_objectives SET period_date_start=:start, period_date_end=:end WHERE map_ref=:ref AND obj_stat=:stat');
            $dbst->execute(array('start' => $strategyMap->startingPeriodDate->format('Y-m-d'),
                'end' => $strategyMap->endingPeriodDate->format('Y-m-d'),
                'ref' => $strategyMap->id,
                'stat' => Objective::TYPE_ACTIVE));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function addObjective(Objective $objective, StrategyMap $strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO smap_objectives(map_ref, obj_desc, pers_ref, theme_ref, period_date_start, period_date_end, obj_stat) '
                    . 'VALUES(:map, :description, :perspective, :theme, :dateStart, :dateEnd, :status)');

            $theme = is_null($objective->theme->id) ? null : $objective->theme->id;

            $dbst->execute(array(
                'map' => $strategyMap->id,
                'description' => $objective->description,
                'perspective' => $objective->perspective->id,
                'theme' => empty($theme) ? null : $theme,
                'dateStart' => $objective->startingPeriodDate->format('Y-m-d'),
                'dateEnd' => $objective->endingPeriodDate->format('Y-m-d'),
                'status' => $objective->environmentStatus
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getObjective($id) {
        try {
            $dbst = $this->db->prepare('SELECT obj_id, obj_desc, shift_desc, agenda_desc, pers_ref, pers_desc, theme_ref, theme_desc, period_date_start, period_date_end, obj_stat '
                    . 'FROM smap_objectives '
                    . 'JOIN smap_perspectives ON pers_ref=pers_id '
                    . 'LEFT JOIN smap_themes ON theme_ref=theme_id '
                    . 'WHERE obj_id=:id');
            $dbst->execute(array('id' => $id));

            $objective = new Objective();
            $objective->perspective = new Perspective();
            $objective->theme = new Theme();
            while ($data = $dbst->fetch()) {
                list($objective->id,
                        $objective->description,
                        $objective->strategicShiftStatement,
                        $objective->agendaStatement,
                        $objective->perspective->id,
                        $objective->perspective->description,
                        $objective->theme->id,
                        $objective->theme->description,
                        $startDate,
                        $endDate,
                        $objective->environmentStatus) = $data;
            }
            $objective->startingPeriodDate = \DateTime::createFromFormat('Y-m-d', $startDate);
            $objective->endingPeriodDate = \DateTime::createFromFormat('Y-m-d', $endDate);
            
            return $objective;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteObjective($id) {
        try {
            $this->db->beginTransaction();
            
            $dbst = $this->db->prepare('DELETE FROM smap_objectives WHERE obj_id=:id');
            $dbst->execute(array('id'=>$id));
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateObjective(Objective $objective) {
        try {
            $this->db->beginTransaction();
            
            $dbst = $this->db->prepare('UPDATE smap_objectives SET obj_desc=:description, '
                    . 'pers_ref=:perspective, '
                    . 'theme_ref=:theme, '
                    . 'period_date_start=:start, '
                    . 'period_date_end=:end '
                    . 'WHERE obj_id=:id');
            $dbst->execute(array('description'=>$objective->description,
                'perspective'=>$objective->perspective->id,
                'theme'=>$objective->theme->id,
                'start'=>$objective->startingPeriodDate->format('Y-m-d'),
                'end'=>$objective->endingPeriodDate->format('Y-m-d'),
                'id'=>$objective->id));
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
