<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\dao\indicator\BaselineDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;

/**
 * Description of BaselineDaoSqlImpl
 *
 * @author britech
 */
class BaselineDaoSqlImpl implements BaselineDao {

    private $db;
    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }
    
    public function deleteBaseline($id) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('DELETE FROM indicators_baseline WHERE baseline_id=:id');
            $dbst->execute(array('id' => $id));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function enlistBaseline(Baseline $baseline, Indicator $indicator) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO indicators_baseline(indicator_ref, group_name, period_year, figure_value, notes) '
                    . 'VALUES(:ref, :group, :year, :value, :notes)');
            $dbst->execute(array('ref' => $indicator->id,
                'group' => $baseline->baselineDataGroup,
                'year' => $baseline->coveredYear,
                'value' => $baseline->value,
                'notes' => $baseline->notes));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateBaseline($baseline) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE indicators_baseline SET '
                    . 'group_name=:group, '
                    . 'period_year=:year, '
                    . 'figure_value=:value, '
                    . 'notes=:notes '
                    . 'WHERE baseline_id=:id');
            $dbst->execute(array(
                'group' => $baseline->baselineDataGroup,
                'year' => $baseline->coveredYear,
                'value' => $baseline->value,
                'notes' => $baseline->notes,
                'id' => $baseline->id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getBaseline($id) {
        try {
            $dbst = $this->db->prepare('SELECT baseline_id, group_name, period_year, figure_value, notes FROM indicators_baseline WHERE baseline_id=:id');
            $dbst->execute(array('id'=>$id));
            
            $baseline = new Baseline();
            while($data = $dbst->fetch()){
                list($baseline->id, 
                        $baseline->baselineDataGroup, 
                        $baseline->coveredYear, 
                        $baseline->value, 
                        $baseline->notes) = $data;
            }
            return $baseline;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
