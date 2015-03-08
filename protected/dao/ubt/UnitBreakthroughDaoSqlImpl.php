<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\ubt\UnitBreakthroughDao;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\dao\commons\DepartmentDaoSqlImpl as DepartmentDao;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl as ObjectiveDao;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl as MeasureProfileDao;
use org\csflu\isms\dao\ubt\LeadMeasureDaoSqlImpl;
use org\csflu\isms\dao\ubt\WigSessionDaoSqlmpl;
/**
 * Description of UnitBreakthroughDaoSqlImpl
 *
 * @author britech
 */
class UnitBreakthroughDaoSqlImpl implements UnitBreakthroughDao {

    private $db;
    private $leadMeasureDaoSource;
    private $departmentDaoSource;
    private $objectiveDaoSource;
    private $measureProfileDaoSource;
    private $wigMeetingDaoSource;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->departmentDaoSource = new DepartmentDao();
        $this->objectiveDaoSource = new ObjectiveDao();
        $this->measureProfileDaoSource = new MeasureProfileDao();
        $this->leadMeasureDaoSource = new LeadMeasureDaoSqlImpl();
        $this->wigMeetingDaoSource = new WigSessionDaoSqlmpl();
    }

    public function getUnitBreakthroughByIdentifier($id) {
        try {
            $dbst = $this->db->prepare('SELECT ubt_id, ubt_stmt, period_date_start, period_date_end, ubt_stat, dept_ref FROM ubt_main WHERE ubt_id=:id');
            $dbst->execute(array('id' => $id));

            $unitBreakthrough = new UnitBreakthrough();
            while ($data = $dbst->fetch()) {
                list($unitBreakthrough->id,
                        $unitBreakthrough->description,
                        $startingPeriod,
                        $endingPeriod,
                        $unitBreakthrough->unitBreakthroughEnvironmentStatus,
                        $department) = $data;
            }

            $unitBreakthrough->startingPeriod = \DateTime::createFromFormat('Y-m-d', $startingPeriod);
            $unitBreakthrough->endingPeriod = \DateTime::createFromFormat('Y-m-d', $endingPeriod);
            $unitBreakthrough->unit = $this->departmentDaoSource->getDepartmentById($department);
            $unitBreakthrough->objectives = $this->listObjectives($unitBreakthrough);
            $unitBreakthrough->measures = $this->listMeasureProfiles($unitBreakthrough);
            $unitBreakthrough->leadMeasures = $this->leadMeasureDaoSource->listLeadMeasures($unitBreakthrough);
            $unitBreakthrough->wigMeetings = $this->wigMeetingDaoSource->listWigSessions($unitBreakthrough);
            
            return $unitBreakthrough;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO ubt_main(map_ref, dept_ref, ubt_stmt, period_date_start, period_date_end) VALUES(:map, :department, :ubt, :start, :end)');
            $dbst->execute(array(
                'map' => $strategyMap->id,
                'department' => $unitBreakthrough->unit->id,
                'ubt' => $unitBreakthrough->description,
                'start' => $unitBreakthrough->startingPeriod->format('Y-m-d'),
                'end' => $unitBreakthrough->endingPeriod->format('Y-m-d')
            ));
            $id = $this->db->lastInsertId();
            $unitBreakthrough->id = $id;

            $this->db->commit();

            $this->linkObjectives($unitBreakthrough);
            $this->linkMeasureProfiles($unitBreakthrough);
            $this->leadMeasureDaoSource->insertLeadMeasures($unitBreakthrough);
            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function linkMeasureProfiles(UnitBreakthrough $unitBreakthrough) {
        try {
            $this->db->beginTransaction();
            foreach ($unitBreakthrough->measures as $measure) {
                $dbst = $this->db->prepare('INSERT INTO ubt_indicator_mapping VALUES(:ubt, :profile)');
                $dbst->execute(array(
                    'ubt' => $unitBreakthrough->id,
                    'profile' => $measure->id
                ));
            }
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function linkObjectives(UnitBreakthrough $unitBreakthrough) {
        try {
            $this->db->beginTransaction();
            foreach ($unitBreakthrough->objectives as $objective) {
                $dbst = $this->db->prepare('INSERT INTO ubt_objective_mapping VALUES(:ubt, :objective)');
                $dbst->execute(array(
                    'ubt' => $unitBreakthrough->id,
                    'objective' => $objective->id
                ));
            }
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listMeasureProfiles(UnitBreakthrough $unitBreakthrough) {
        try {
            $dbst = $this->db->prepare('SELECT mp_ref FROM ubt_indicator_mapping WHERE ubt_ref=:ref');
            $dbst->execute(array('ref' => $unitBreakthrough->id));

            $measureProfiles = array();
            while ($data = $dbst->fetch()) {
                list($measure) = $data;
                array_push($measureProfiles, $this->measureProfileDaoSource->getMeasureProfile($measure));
            }
            return $measureProfiles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listObjectives(UnitBreakthrough $unitBreakthrough) {
        try {
            $dbst = $this->db->prepare('SELECT obj_ref FROM ubt_objective_mapping WHERE ubt_ref=:ref');
            $dbst->execute(array('ref' => $unitBreakthrough->id));

            $objectives = array();
            while ($data = $dbst->fetch()) {
                list($objective) = $data;
                array_push($objectives, $this->objectiveDaoSource->getObjective($objective));
            }
            return $objectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listUnitBreakthroughByDepartment(Department $department) {
        try {
            $dbst = $this->db->prepare('SELECT ubt_id FROM ubt_main WHERE dept_ref=:department');
            $dbst->execute(array('department' => $department->id));
            
            $unitBreakthroughs = array();
            while($data = $dbst->fetch()){
                list($id) = $data;
                array_push($unitBreakthroughs, $this->getUnitBreakthroughByIdentifier($id));
            }
            return $unitBreakthroughs;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listUnitBreakthroughByStrategyMap(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT ubt_id FROM ubt_main WHERE map_ref=:ref');
            $dbst->execute(array('ref' => $strategyMap->id));

            $unitBreakthroughs = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                array_push($unitBreakthroughs, $this->getUnitBreakthroughByIdentifier($id));
            }
            return $unitBreakthroughs;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateUnitBreakthrough(UnitBreakthrough $unitBreakthrough) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE ubt_main SET ubt_stmt=:ubt, period_date_start=:start, period_date_end=:end, dept_ref=:unit WHERE ubt_id=:id');
            $dbst->execute(array(
                'ubt' => $unitBreakthrough->description,
                'start' => $unitBreakthrough->startingPeriod->format('Y-m-d'),
                'end' => $unitBreakthrough->endingPeriod->format('Y-m-d'),
                'unit' => $unitBreakthrough->unit->id,
                'id' => $unitBreakthrough->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getUnitBreakthroughByLeadMeasure(LeadMeasure $leadMeasure) {
        try {
            $dbst = $this->db->prepare('SELECT ubt_ref FROM lm_main WHERE lm_id=:id');
            $dbst->execute(array(
                'id' => $leadMeasure->id
            ));

            while ($data = $dbst->fetch()) {
                list($id) = $data;
            }
            return $this->getUnitBreakthroughByIdentifier($id);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function unlinkMeasureProfile(UnitBreakthrough $unitBreakthrough, MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ubt_indicator_mapping WHERE ubt_ref=:ubt AND mp_ref=:mp');
            $dbst->execute(array(
                'ubt' => $unitBreakthrough->id,
                'mp' => $measureProfile->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function unlinkObjective(UnitBreakthrough $unitBreakthrough, Objective $objective) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ubt_objective_mapping WHERE ubt_ref=:ubt AND obj_ref=:objective');
            $dbst->execute(array(
                'ubt' => $unitBreakthrough->id,
                'objective' => $objective->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
