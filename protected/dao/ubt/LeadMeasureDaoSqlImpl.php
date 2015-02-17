<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\ubt\LeadMeasureDao;
use org\csflu\isms\models\ubt\UnitBreakthrough;

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
                $dbst = $this->db->prepare('INSERT INTO lm_main(lm_desc, ubt_ref) VALUES(:description, :ubt)');
                $dbst->execute(array(
                    'description' => $leadMeasure->description,
                    'ubt' => $unitBreakthrough->id
                ));
            }

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
