<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\dao\commons\UnitOfMeasureDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\core\ConnectionManager;

/**
 * Description of UnitOfMeasureDaoSqlImpl
 *
 * @author britech
 */
class UnitOfMeasureDaoSqlImpl implements UnitOfMeasureDao {

    public function listUnitOfMeasures() {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $dbst = $db->prepare('SELECT uom_id, uom_symbol, uom_desc FROM uom ORDER BY uom_desc ASC');
            $dbst->execute();

            $uoms = array();
            while ($data = $dbst->fetch()) {
                $uom = new UnitOfMeasure();
                list($uom->id, $uom->symbol, $uom->description) = $data;
                array_push($uoms, $uom);
            }
            return $uoms;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
