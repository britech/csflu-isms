<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\core\ConnectionManager as ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;
use org\csflu\isms\dao\commons\PositionDao as PositionDao;
use org\csflu\isms\models\commons\Position as Position;
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
}
