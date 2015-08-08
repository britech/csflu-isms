<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\dao\commons\UnitOfMeasureDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\core\DatabaseConnectionManager;
use org\csflu\isms\util\ApplicationLoggerUtils;

/**
 * Description of UnitOfMeasureDaoSqlImpl
 *
 * @author britech
 */
class UnitOfMeasureDaoSqlImpl implements UnitOfMeasureDao {

    private $logger;
    private $db;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->db = DatabaseConnectionManager::getInstance()->getMainDbConnection();
    }

    public function listUnitOfMeasures() {
        try {
            $dbst = $this->db->prepare('SELECT uom_id, uom_symbol, uom_desc FROM uom ORDER BY uom_desc ASC');
            $dbst->execute();
            ApplicationLoggerUtils::logSql($this->logger, $dbst);

            $uoms = array();
            while ($data = $dbst->fetch()) {
                $uom = new UnitOfMeasure();
                list($uom->id, $uom->symbol, $uom->description) = $data;
                $uoms = array_merge($uoms, array($uom));
            }
            return $uoms;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function enlistUom(UnitOfMeasure $uom) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('INSERT INTO uom(uom_symbol, uom_desc) VALUES(:symbol, :desc)');

            $params = array('symbol' => $uom->symbol, 'desc' => $uom->description);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function updateUom(UnitOfMeasure $uom) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('UPDATE uom SET uom_symbol=:symbol, uom_desc=:desc WHERE uom_id=:id');

            $params = array('symbol' => $uom->symbol, 'desc' => $uom->description, 'id' => $uom->id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function getUomInfo($id) {
        try {
            $dbst = $this->db->prepare('SELECT uom_id, uom_symbol, uom_desc FROM uom WHERE uom_id=:id');
            $params = array('id' => $id);
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $uom = new UnitOfMeasure();

            while ($data = $dbst->fetch()) {
                list($uom->id, $uom->symbol, $uom->description) = $data;
            }
            return $uom;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

}
