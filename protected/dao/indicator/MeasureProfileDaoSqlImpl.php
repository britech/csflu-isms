<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\dao\indicator\MeasureProfileDao;
use org\csflu\isms\dao\indicator\IndicatorDaoSqlImpl;
use org\csflu\isms\dao\map\ObjectiveDaoSqlImpl;
use org\csflu\isms\dao\commons\DepartmentDaoSqlImpl;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\Target;

/**
 * Description of MeasureProfileDaoSqlImpl
 *
 * @author britech
 */
class MeasureProfileDaoSqlImpl implements MeasureProfileDao {

    private $db;
    private $indicatorDataSource;
    private $objectiveDataSource;
    private $departmentDaoSource;
    private $movementDaoSource;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->indicatorDataSource = new IndicatorDaoSqlImpl();
        $this->objectiveDataSource = new ObjectiveDaoSqlImpl();
        $this->departmentDaoSource = new DepartmentDaoSqlImpl();
        $this->movementDaoSource = new MeasureProfileMovementDaoSqlImpl();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMeasureProfiles(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT mp_id FROM mp_main JOIN smap_objectives ON obj_ref=obj_id WHERE map_ref=:map');
            $dbst->execute(array('map' => $strategyMap->id));

            $profiles = array();

            while ($data = $dbst->fetch()) {
                list($id) = $data;
                array_push($profiles, $this->getMeasureProfile($id));
            }

            return $profiles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertMeasureProfile(MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO mp_main(obj_ref, indicator_ref, measure_type, mp_freq, mp_stat, period_start_date, period_end_date) VALUES(:objective, :indicator, :type, :frequency, :status, :start, :end)');
            $dbst->execute(array(
                'objective' => $measureProfile->objective->id,
                'indicator' => $measureProfile->indicator->id,
                'type' => $measureProfile->measureType,
                'frequency' => $measureProfile->frequencyOfMeasure,
                'status' => $measureProfile->measureProfileEnvironmentStatus,
                'start' => $measureProfile->timelineStart->format('Y-m-d'),
                'end' => $measureProfile->timelineEnd->format('Y-m-d')
            ));

            $id = $this->db->lastInsertId();

            $this->db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getMeasureProfile($id) {
        try {
            $dbst = $this->db->prepare('SELECT mp_id, measure_type, mp_freq, mp_stat, obj_ref, indicator_ref, period_start_date, period_end_date FROM mp_main WHERE mp_id=:id');
            $dbst->execute(array('id' => $id));

            $measureProfile = new MeasureProfile();

            while ($data = $dbst->fetch()) {
                list($measureProfile->id, $measureProfile->measureType, $measureProfile->frequencyOfMeasure, $measureProfile->measureProfileEnvironmentStatus, $objective, $indicator, $start, $end) = $data;
            }

            $measureProfile->indicator = $this->indicatorDataSource->retrieveIndicator($indicator);
            $measureProfile->objective = $this->objectiveDataSource->getObjective($objective);
            $measureProfile->timelineStart = \DateTime::createFromFormat('Y-m-d', $start);
            $measureProfile->timelineEnd = \DateTime::createFromFormat('Y-m-d', $end);
            $measureProfile->leadOffices = $this->listLeadOffices($measureProfile);
            $measureProfile->targets = $this->listTargets($measureProfile);
            $measureProfile->movements = $this->movementDaoSource->listMeasureProfileMovements($measureProfile);

            return $measureProfile;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listLeadOffices(MeasureProfile $measureProfile) {
        try {
            $dbst = $this->db->prepare('SELECT mprc_id, dept_ref, type FROM mp_rc WHERE mp_ref=:ref');
            $dbst->execute(array('ref' => $measureProfile->id));

            $leadOffices = array();

            while ($data = $dbst->fetch()) {
                $leadOffice = new LeadOffice();
                list($leadOffice->id, $department, $leadOffice->designation) = $data;
                $leadOffice->department = $this->departmentDaoSource->getDepartmentById($department);
                array_push($leadOffices, $leadOffice);
            }

            return $leadOffices;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertLeadOffices(MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            foreach ($measureProfile->leadOffices as $leadOffice) {
                $dbst = $this->db->prepare('INSERT INTO mp_rc(mp_ref, dept_ref, type) VALUES(:measure, :department, :type)');
                $dbst->execute(array(
                    'measure' => $measureProfile->id,
                    'department' => $leadOffice->department->id,
                    'type' => $leadOffice->designation
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listTargets(MeasureProfile $measureProfile) {
        try {
            $dbst = $this->db->prepare('SELECT target_id, data_group, covered_year, value, notes '
                    . 'FROM mp_targets WHERE mp_ref=:ref '
                    . 'ORDER BY covered_year ASC, data_group ASC');
            $dbst->execute(array('ref' => $measureProfile->id));

            $targets = array();
            while ($data = $dbst->fetch()) {
                $target = new Target();
                list($target->id,
                        $target->dataGroup,
                        $target->coveredYear,
                        $target->value,
                        $target->notes) = $data;
                array_push($targets, $target);
            }

            return $targets;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertTargets(MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            foreach ($measureProfile->targets as $target) {
                $dbst = $this->db->prepare('INSERT INTO mp_targets(mp_ref, data_group, covered_year, value, notes) '
                        . 'VALUES(:measure, :group, :year, :value, :notes)');
                $dbst->execute(array(
                    'measure' => $measureProfile->id,
                    'group' => $target->dataGroup,
                    'year' => $target->coveredYear,
                    'value' => $target->value,
                    'notes' => $target->notes
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateMeasureProfile(MeasureProfile $measureProfile) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE mp_main SET obj_ref=:objective, '
                    . 'indicator_ref=:indicator, '
                    . 'measure_type=:type, '
                    . 'mp_freq=:frequency, '
                    . 'mp_stat=:stat, '
                    . 'period_start_date=:start, '
                    . 'period_end_date=:end WHERE mp_id=:id');
            $dbst->execute(array('objective' => $measureProfile->objective->id,
                'indicator' => $measureProfile->indicator->id,
                'type' => $measureProfile->measureType,
                'frequency' => $measureProfile->frequencyOfMeasure,
                'stat' => $measureProfile->measureProfileEnvironmentStatus,
                'start' => $measureProfile->timelineStart->format('Y-m-d'),
                'end' => $measureProfile->timelineEnd->format('Y-m-d'),
                'id' => $measureProfile->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getMeasureProfileByLeadOffice(LeadOffice $leadOffice) {
        try {
            $dbst = $this->db->prepare('SELECT mp_ref FROM mp_rc WHERE mprc_id=:id');
            $dbst->execute(array('id' => $leadOffice->id));

            while ($data = $dbst->fetch()) {
                list($profile) = $data;
            }
            return $this->getMeasureProfile($profile);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateLeadOffice(LeadOffice $leadOffice) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE mp_rc SET type=:type WHERE mprc_id=:id');
            $dbst->execute(array('type' => $leadOffice->designation, 'id' => $leadOffice->id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteLeadOffice($id) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM mp_rc WHERE mprc_id=:id');
            $dbst->execute(array('id' => $id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getMeasureProfileByTarget(Target $target) {
        try {
            $dbst = $this->db->prepare('SELECT mp_ref FROM mp_targets WHERE target_id=:id');
            $dbst->execute(array('id' => $target->id));

            while ($data = $dbst->fetch()) {
                list($profile) = $data;
            }
            return $this->getMeasureProfile($profile);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateTarget(Target $target) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE mp_targets SET data_group=:group, covered_year=:year, value=:value, notes=:notes WHERE target_id=:id');
            $dbst->execute(array('group' => $target->dataGroup,
                'year' => $target->coveredYear,
                'value' => $target->value,
                'notes' => $target->notes,
                'id' => $target->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteTarget($id) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM mp_targets WHERE target_id=:id');
            $dbst->execute(array('id' => $id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
