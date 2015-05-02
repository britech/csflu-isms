<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\ubt\LeadMeasureDao;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\ubt\LeadMeasure;

/**
 * Description of LeadMeasureDaoSqlImpl
 *
 * @author britech
 */
class LeadMeasureDaoSqlImpl implements LeadMeasureDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function insertLeadMeasures(UnitBreakthrough $unitBreakthrough) {
        try {
            $this->db->beginTransaction();

            foreach ($unitBreakthrough->leadMeasures as $leadMeasure) {
                $dbst = $this->db->prepare('INSERT INTO lm_main(lm_desc, lm_baseline, lm_target, uom_ref, period_start_date, period_end_date, lm_designation, lm_status, ubt_ref) '
                        . 'VALUES(:description, :baseline, :target, :uom, :start, :end, :designation, :status, :ubt)');
                $dbst->execute(array(
                    'description' => $leadMeasure->description,
                    'baseline' => $leadMeasure->baselineFigure,
                    'target' => $leadMeasure->targetFigure,
                    'uom' => $leadMeasure->uom->id,
                    'start' => $leadMeasure->startingPeriod->format('Y-m-d'),
                    'end' => $leadMeasure->endingPeriod->format('Y-m-d'),
                    'designation' => $leadMeasure->designation,
                    'status' => $leadMeasure->leadMeasureEnvironmentStatus,
                    'ubt' => $unitBreakthrough->id
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listLeadMeasures(UnitBreakthrough $unitBreakthrough) {
        try {
            $dbst = $this->db->prepare('SELECT lm_id FROM lm_main WHERE ubt_ref=:ubt');
            $dbst->execute(array('ubt' => $unitBreakthrough->id));

            $leadMeasures = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                array_push($leadMeasures, $this->getLeadMeasure($id));
            }

            return $leadMeasures;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getLeadMeasure($id) {
        try {
            $dbst = $this->db->prepare('SELECT lm_id, lm_desc, lm_status FROM lm_main WHERE lm_id=:id');
            $dbst->execute(array('id' => $id));

            $leadMeasure = new LeadMeasure();
            while ($data = $dbst->fetch()) {
                list($leadMeasure->id,
                        $leadMeasure->description,
                        $leadMeasure->leadMeasureEnvironmentStatus) = $data;
            }
            return $leadMeasure;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateLeadMeasure(LeadMeasure $leadMeasure) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('UPDATE lm_main SET lm_desc=:description, lm_status=:status WHERE lm_id=:id');
            $dbst->execute(array(
                'description' => $leadMeasure->description,
                'status' => $leadMeasure->leadMeasureEnvironmentStatus,
                'id' => $leadMeasure->id
            ));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
