<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\map\PerspectiveDao;
use org\csflu\isms\models\map\Perspective;

/**
 * Description of PerspectiveDaoSqlImpl
 *
 * @author britech
 */
class PerspectiveDaoSqlImpl implements PerspectiveDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function insertPerspective($perspective, $strategyMap) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('INSERT INTO smap_perspectives(pers_desc, pos_order, map_ref) VALUES(:description, :order, :ref)');
            $dbst->execute(array('description' => $perspective->description,
                'order' => $perspective->positionOrder,
                'ref' => $strategyMap->id));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listAllPerspectives() {
        try {
            $dbst = $this->db->prepare('SELECT DISTINCT(pers_desc) FROM smap_perspectives ORDER BY pers_desc');
            $dbst->execute();

            $perspectives = array();
            while ($data = $dbst->fetch()) {
                $perspective = new Perspective();
                list($perspective->description) = $data;
                array_push($perspectives, $perspective);
            }
            return $perspectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listPerspectivesByStrategyMap($strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT pers_id, pers_desc, pos_order FROM smap_perspectives WHERE map_ref=:ref ORDER BY pos_order ASC');
            $dbst->execute(array('ref' => $strategyMap->id));

            $perspectives = array();
            while ($data = $dbst->fetch()) {
                $perspective = new Perspective();
                list($perspective->id, $perspective->description, $perspective->positionOrder) = $data;
                array_push($perspectives, $perspective);
            }
            return $perspectives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updatePerspective($perspective) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('UPDATE smap_perspectives SET pers_desc=:description WHERE pers_id=:id');
            $dbst->execute(array('description'=>$perspective->description, 'id'=>$perspective->id));
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getPerspective($id) {
        try{
            $dbst = $this->db->prepare('SELECT pers_id, pers_desc, pos_order FROM smap_perspectives WHERE pers_id=:id');
            $dbst->execute(array('id'=>$id));
            
            $perspective = new Perspective();
            while($data = $dbst->fetch()){
                list($perspective->id, $perspective->description, $perspective->positionOrder) = $data;
            }
            return $perspective;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deletePerspective($id) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('DELETE FROM smap_perspectives WHERE pers_id=:id');
            $dbst->execute(array('id'=>$id));
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
