<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\core\DatabaseConnectionManager;
use org\csflu\isms\util\ApplicationLoggerUtils;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\commons\PositionDao;
use org\csflu\isms\models\commons\Position;
/**
 * Description of PositionDaoSqlImpl
 *
 * @author britech
 */
class PositionDaoSqlImpl implements PositionDao{
    
    private $logger;
    private $db;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);

        $connectionManager = DatabaseConnectionManager::getInstance();
        $this->db = $connectionManager->getMainDbConnection();
    }

    public function listPositions() {
        try{
            $dbst = $this->db->prepare("SELECT pos_id, pos_desc FROM positions ORDER BY pos_desc ASC");
            $dbst->execute();
            ApplicationLoggerUtils::logSql($this->logger, $dbst);

            $positions = array();
            while($data = $dbst->fetch()){
                $position = new Position();
                list($position->id, $position->name) = $data;
                array_push($positions, $position);
            }
            return $positions;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function enlistPosition(Position $position) {
        try {
            $this->db->beginTransaction();
            
            $params = array('description'=>$position->name);
            $dbst = $this->db->prepare('INSERT INTO positions(pos_desc) VALUES(:description)');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function getPositionData($id) {
        try {
            $params = array('id'=>$id);
            $dbst = $this->db->prepare('SELECT pos_id, pos_desc FROM positions WHERE pos_id=:id');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $position = new Position();
            while($data = $dbst->fetch()){
                list($position->id, $position->name) = $data;
            }
            return $position;
        } catch (\PDException $ex) {
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

    public function updatePosition(Position $position) {
        try {
            $this->db->beginTransaction();
            
            $params = array('desc'=>$position->name, 'id'=>$position->id);
            $dbst = $this->db->prepare('UPDATE positions SET pos_desc=:desc WHERE pos_id=:id');
            $dbst->execute($params);
            ApplicationLoggerUtils::logSql($this->logger, $dbst, $params);
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage(), $ex);
        }
    }

}
