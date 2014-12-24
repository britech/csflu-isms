<?php

namespace org\csflu\isms\dao\indicator;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\dao\indicator\IndicatorDao;
use org\csflu\isms\dao\commons\UnitOfMeasureDaoSqlImpl as UnitOfMeasureDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;

/**
 * Description of IndicatorDaoSqlImpl
 *
 * @author britech
 */
class IndicatorDaoSqlImpl implements IndicatorDao {

    private $db;
    private $uomDaoSource;

    public function __construct() {
        $this->uomDaoSource = new UnitOfMeasureDao();
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function listIndicators() {
        try {
            $dbst = $this->db->prepare('SELECT indicator_id, '
                    . 'indicator_description, '
                    . 'indicator_rationale, '
                    . 'formula_description, '
                    . 'data_src, '
                    . 'data_src_stat,'
                    . 'data_src_avail_date '
                    . 'FROM indicators '
                    . 'ORDER BY indicator_description');
            $dbst->execute();

            $indicators = array();
            while ($data = $dbst->fetch()) {
                $indicator = new Indicator();
                list($indicator->id,
                        $indicator->description,
                        $indicator->rationale,
                        $indicator->formula,
                        $indicator->dataSource,
                        $indicator->dataSourceStatus,
                        $indicator->dataSourceAvailabilityDate) = $data;
                array_push($indicators, $indicator);
            }
            return $indicators;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function enlistIndicator($indicator) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO indicators(indicator_description, indicator_rationale, formula_description, data_src, data_src_stat, data_src_avail_date, uom)'
                    . ' VALUES(:description, :rationale, :formula, :dataSource, :dataStat, :dataAvailDate, :uom)');
            $dbst->execute(array(
                'description' => $indicator->description,
                'rationale' => $indicator->rationale,
                'formula' => $indicator->formula,
                'dataSource' => $indicator->dataSource,
                'dataStat' => $indicator->dataSourceStatus,
                'dataAvailDate' => $indicator->dataSourceAvailabilityDate,
                'uom' => $indicator->uom->id
            ));

            $id = $this->db->lastInsertId();

            $this->db->commit();

            return $id;
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function retrieveIndicator($id) {
        try {
            $dbst = $this->db->prepare('SELECT '
                    . 'indicator_id, '
                    . 'indicator_description, '
                    . 'indicator_rationale, '
                    . 'formula_description, '
                    . 'data_src, '
                    . 'data_src_stat, '
                    . 'data_src_avail_date, '
                    . 'uom '
                    . 'FROM indicators '
                    . 'WHERE indicator_id=:id');
            $dbst->execute(array('id' => $id));

            $indicator = new Indicator();


            while ($data = $dbst->fetch()) {
                list($indicator->id,
                        $indicator->description,
                        $indicator->rationale,
                        $indicator->formula,
                        $indicator->dataSource,
                        $indicator->dataSourceStatus,
                        $indicator->dataSourceAvailabilityDate,
                        $uom) = $data;
            }

            $indicator->uom = $this->uomDaoSource->getUomInfo($uom);
            $indicator->baselineData = $this->retrieveIndicatorBaselineList($indicator);

            return $indicator;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    function retrieveIndicatorBaselineList($indicator) {
        try {
            $dbst = $this->db->prepare('SELECT baseline_id, group_name, period_year, figure_value, notes '
                    . 'FROM indicators_baseline '
                    . 'WHERE indicator_ref=:ref '
                    . 'ORDER BY period_year ASC, group_name ASC');
            $dbst->execute(array('ref' => $indicator->id));

            $baselineData = array();
            while ($data = $dbst->fetch()) {
                $baseline = new Baseline();
                list($baseline->id,
                        $baseline->baselineDataGroup,
                        $baseline->coveredYear,
                        $baseline->value,
                        $baseline->notes) = $data;
                array_push($baselineData, $baseline);
            }
            return $baselineData;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateIndicator($indicator) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE indicators SET indicator_description=:description, '
                    . 'indicator_rationale=:rationale, '
                    . 'formula_description=:formula, '
                    . 'data_src=:dataSource, '
                    . 'data_src_stat=:dataStat, '
                    . 'data_src_avail_date=:date, '
                    . 'uom=:uom '
                    . 'WHERE indicator_id=:id');
            $dbst->execute(array(
                'description' => $indicator->description,
                'rationale' => $indicator->rationale,
                'formula' => $indicator->formula,
                'dataSource' => $indicator->dataSource,
                'dataStat' => $indicator->dataSourceStatus,
                'date' => $indicator->dataSourceAvailabilityDate,
                'uom' => $indicator->uom->id,
                'id' => $indicator->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function retrieveIndicatorByBaseline(Baseline $baseline) {
        try {
            $dbst = $this->db->prepare('SELECT indicator_ref FROM indicators_baseline WHERE baseline_id=:id');
            $dbst->execute(array('id' => $baseline->id));
            while ($data = $dbst->fetch()) {
                list($id) = $data;
            }
            return $this->retrieveIndicator($id);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
