<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\initiative\InitiativeDao;
use org\csflu\isms\dao\commons\DepartmentDaoSqlImpl;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl;
use org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl;
use org\csflu\isms\dao\initiative\PhaseDaoSqlImpl;

/**
 * Description of InitiativeDaoSqlImpl
 *
 * @author britech
 */
class InitiativeDaoSqlImpl implements InitiativeDao {

    private $db;
    private $departmentDaoSource;
    private $objectiveDaoSource;
    private $measureProfileDaoSource;
    private $phaseDaoSource;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->departmentDaoSource = new DepartmentDaoSqlImpl();
        $this->objectiveDaoSource = new ObjectiveDaoSqlImpl();
        $this->measureProfileDaoSource = new MeasureProfileDaoSqlImpl();
        $this->phaseDaoSource = new PhaseDaoSqlImpl();
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT ini_id, ini_name FROM ini_main WHERE map_ref=:ref');
            $dbst->execute(array('ref' => $strategyMap->id));

            $initiatives = array();
            while ($data = $dbst->fetch()) {
                $initiative = new Initiative();
                list($initiative->id, $initiative->title) = $data;
                array_push($initiatives, $initiative);
            }
            return $initiatives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertInitiative(Initiative $initiative, StrategyMap $strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO ini_main(ini_name, ini_desc, ini_benf, period_start_date, period_end_date, eo_num, ini_advisers, ini_stat, map_ref) VALUES(:name, :description, :beneficiaries, :start, :end, :eoNumber, :advisers, :status, :map)');
            $dbst->execute(array(
                'name' => $initiative->title,
                'description' => $initiative->description,
                'beneficiaries' => $initiative->beneficiaries,
                'start' => $initiative->startingPeriod->format('Y-m-d'),
                'end' => $initiative->endingPeriod->format('Y-m-d'),
                'eoNumber' => $initiative->eoNumber,
                'advisers' => $initiative->advisers,
                'status' => $initiative->initiativeEnvironmentStatus,
                'map' => $strategyMap->id
            ));

            $id = $this->db->lastInsertId();

            $this->db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function addImplementingOffices(Initiative $initiative) {
        try {
            $this->db->beginTransaction();

            foreach ($initiative->implementingOffices as $implementingOffice) {
                $dbst = $this->db->prepare('INSERT INTO ini_teams(ini_ref, dept_ref, team_type) VALUES(:initiative, :department, :designation)');
                $dbst->execute(array(
                    'initiative' => $initiative->id,
                    'department' => $implementingOffice->department->id,
                    'designation' => $implementingOffice->designation
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function linkLeadMeasures(Initiative $initiative) {
        try {
            $this->db->beginTransaction();

            foreach ($initiative->leadMeasures as $leadMeasure) {
                $dbst = $this->db->prepare('INSERT INTO ini_indicator_mapping VALUES(:initiative, :leadMeasure)');
                $dbst->execute(array(
                    'initiative' => $initiative->id,
                    'leadMeasure' => $leadMeasure->id
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function linkObjectives(Initiative $initiative) {
        try {
            $this->db->beginTransaction();

            foreach ($initiative->objectives as $objective) {
                $dbst = $this->db->prepare('INSERT INTO ini_objective_mapping VALUES(:initiative, :objective)');
                $dbst->execute(array(
                    'initiative' => $initiative->id,
                    'objective' => $objective->id
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getInitiative($id) {
        try {
            $dbst = $this->db->prepare('SELECT ini_id, ini_name, ini_desc, ini_benf, period_start_date, period_end_date, eo_num, ini_advisers, ini_stat FROM ini_main WHERE ini_id=:id');
            $dbst->execute(array('id' => $id));

            $initiative = new Initiative();
            while ($data = $dbst->fetch()) {
                list($initiative->id,
                        $initiative->title,
                        $initiative->description,
                        $initiative->beneficiaries,
                        $start,
                        $end,
                        $initiative->eoNumber,
                        $initiative->advisers,
                        $initiative->initiativeEnvironmentStatus) = $data;
            }

            $initiative->startingPeriod = \DateTime::createFromFormat('Y-m-d', $start);
            $initiative->endingPeriod = \DateTime::createFromFormat('Y-m-d', $end);
            $initiative->implementingOffices = $this->listImplementingOffices($initiative);
            $initiative->objectives = $this->listObjectives($initiative);
            $initiative->leadMeasures = $this->listLeadMeasures($initiative);
            $initiative->phases = $this->phaseDaoSource->listPhases($initiative);

            return $initiative;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateInitiative(Initiative $initiative) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE ini_main SET ini_name=:name, ini_desc=:description, ini_benf=:beneficiaries, period_start_date=:start, period_end_date=:end, eo_num=:eoNumber, ini_advisers=:advisers, ini_stat=:status WHERE ini_id=:id');
            $dbst->execute(array(
                'name' => $initiative->title,
                'description' => $initiative->description,
                'beneficiaries' => $initiative->beneficiaries,
                'start' => $initiative->startingPeriod->format('Y-m-d'),
                'end' => $initiative->endingPeriod->format('Y-m-d'),
                'eoNumber' => $initiative->eoNumber,
                'advisers' => $initiative->advisers,
                'status' => $initiative->initiativeEnvironmentStatus,
                'id' => $initiative->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listImplementingOffices(Initiative $initiative) {
        try {
            $dbst = $this->db->prepare('SELECT team_id, team_type, dept_ref FROM ini_teams WHERE ini_ref=:initiative');
            $dbst->execute(array('initiative' => $initiative->id));

            $implementingOffices = array();

            while ($data = $dbst->fetch()) {
                $implementingOffice = new ImplementingOffice();
                list($implementingOffice->id, $implementingOffice->designation, $department) = $data;
                $implementingOffice->department = $this->departmentDaoSource->getDepartmentById($department);
                array_push($implementingOffices, $implementingOffice);
            }

            return $implementingOffices;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteImplementingOffice(ImplementingOffice $implementingOffice) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ini_teams WHERE team_id=:id');
            $dbst->execute(array('id' => $implementingOffice->id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listObjectives(Initiative $initiative) {
        try {
            $dbst = $this->db->prepare('SELECT obj_ref FROM ini_objective_mapping WHERE ini_ref=:ref');
            $dbst->execute(array('ref' => $initiative->id));

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

    public function listLeadMeasures(Initiative $initiative) {
        try {
            $dbst = $this->db->prepare('SELECT mp_ref FROM ini_indicator_mapping WHERE ini_ref=:ref');
            $dbst->execute(array('ref' => $initiative->id));

            $leadMeasures = array();
            while ($data = $dbst->fetch()) {
                list($leadMeasure) = $data;
                array_push($leadMeasures, $this->measureProfileDaoSource->getMeasureProfile($leadMeasure));
            }
            return $leadMeasures;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function unlinkObjective(Initiative $initiative, Objective $objective) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ini_objective_mapping WHERE ini_ref=:initiative AND obj_ref=:objective');
            $dbst->execute(array(
                'initiative' => $initiative->id,
                'objective' => $objective->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function unlinkLeadMeasure(Initiative $initiative, MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ini_indicator_mapping WHERE ini_ref=:initiative AND mp_ref=:measure');
            $dbst->execute(array(
                'initiative' => $initiative->id,
                'measure' => $measureProfile->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
