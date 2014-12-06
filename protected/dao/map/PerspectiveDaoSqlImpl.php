<?php

namespace org\csflu\isms\dao\map;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\map\PerspectiveDao;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;

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

    public function insertPerspective(Perspective $perspective, StrategyMap $strategyMap) {
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

    public function listPerspectivesByStrategyMap(StrategyMap $strategyMap) {
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

    public function updatePerspective(Perspective $perspective) {
        try {
            $this->db->beginTransaction();
            $dbst = $this->db->prepare('UPDATE smap_perspectives SET pers_desc=:description WHERE pers_id=:id');
            $dbst->execute(array('description' => $perspective->description, 'id' => $perspective->id));
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getPerspective($id) {
        try {
            $dbst = $this->db->prepare('SELECT pers_id, pers_desc, pos_order FROM smap_perspectives WHERE pers_id=:id');
            $dbst->execute(array('id' => $id));

            $perspective = new Perspective();
            while ($data = $dbst->fetch()) {
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
            
            $dbst1 = $this->db->prepare('DELETE FROM smap_objectives WHERE pers_ref=:ref');
            $dbst1->execute(array('ref'=>$id));
            
            $dbst2 = $this->db->prepare('DELETE FROM smap_perspectives WHERE pers_id=:id');
            $dbst2->execute(array('id' => $id));
            
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listAllThemes() {
        try {
            $dbst = $this->db->prepare('SELECT DISTINCT(theme_desc) FROM smap_themes ORDER BY theme_desc ASC');
            $dbst->execute();

            $themes = array();
            while ($data = $dbst->fetch()) {
                $theme = new Theme();
                list($theme->description) = $data;
                array_push($themes, $theme);
            }
            return $themes;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listThemesByStrategyMap(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT theme_id, theme_desc FROM smap_themes WHERE map_ref=:ref ORDER BY theme_desc ASC');
            $dbst->execute(array('ref' => $strategyMap->id));

            $themes = array();
            while ($data = $dbst->fetch()) {
                $theme = new Theme();
                list($theme->id, $theme->description) = $data;
                array_push($themes, $theme);
            }
            return $themes;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteTheme($id) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM smap_themes WHERE theme_id=:id');
            $dbst->execute(array('id' => $id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function insertTheme(Theme $theme, StrategyMap $strategyMap) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO smap_themes(theme_desc, map_ref) VALUES(:description, :map)');
            $dbst->execute(array('description' => $theme->description, 'map' => $strategyMap->id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateTheme(Theme $theme) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE smap_themes SET theme_desc=:description WHERE theme_id=:id');
            $dbst->execute(array('description' => $theme->description, 'id' => $theme->id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getTheme($id) {
        try {
            $dbst = $this->db->prepare('SELECT theme_id, theme_desc FROM smap_themes WHERE theme_id=:id');
            $dbst->execute(array('id'=>$id));
            
            $theme = new Theme();
            while($data = $dbst->fetch()){
                list($theme->id, $theme->description) = $data;
            }
            return $theme;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
