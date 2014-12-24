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

    public function deleteBaseline($id) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('DELETE FROM indicators_baseline WHERE baseline_id=:id');
            $dbst->execute(array('id' => $id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function enlistBaseline(Baseline $baseline, Indicator $indicator) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO indicators_baseline(indicator_ref, group_name, period_year, figure_value, notes) '
                    . 'VALUES(:ref, :group, :year, :value, :notes)');
            $dbst->execute(array('ref' => $indicator->id,
                'group' => $baseline->baselineDataGroup,
                'year' => $baseline->coveredYear,
                'value' => $baseline->value,
                'notes' => $baseline->notes));

            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateBaseline($baseline) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('UPDATE indicators_baseline SET '
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

            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
