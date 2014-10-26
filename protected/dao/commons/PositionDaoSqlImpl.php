<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\commons\PositionDao;
use org\csflu\isms\models\commons\Position;
/**
 * Description of PositionDaoSqlImpl
 *
 * @author britech
 */
class PositionDaoSqlImpl implements PositionDao{
    
    public function listPositions() {
        $db = ConnectionManager::getConnectionInstance();
        try{
            $dbst = $db->prepare("SELECT pos_id, pos_desc FROM positions ORDER BY pos_desc ASC");
            $dbst->execute();
            
            $positions = array();
            while($data = $dbst->fetch()){
                $position = new Position();
                list($position->id, $position->name) = $data;
                array_push($positions, $position);
            }
            return $positions;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function enlistPosition($position) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();
            
            $dbst = $db->prepare('INSERT INTO positions(pos_desc) VALUES(:description)');
            $dbst->execute(array('description'=>$position->name));
            
            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
