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

    public function enlistUom($uom) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('INSERT INTO uom(uom_symbol, uom_desc) VALUES(:symbol, :desc)');
            $dbst->execute(array('symbol' => $uom->symbol, 'desc' => $uom->description));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateUom($uom) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            $dbst = $db->prepare('UPDATE uom SET uom_symbol=:symbol, uom_desc=:desc WHERE uom_id=:id');
            $dbst->execute(array('symbol' => $uom->symbol, 'desc' => $uom->description, 'id' => $uom->id));
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getUomInfo($id) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $dbst = $db->prepare('SELECT uom_id, uom_symbol, uom_desc FROM uom WHERE uom_id=:id');
            $dbst->execute(array('id' => $id));

            $uom = new UnitOfMeasure();

            while ($data = $dbst->fetch()) {
                list($uom->id, $uom->symbol, $uom->description) = $data;
            }
            return $uom;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
