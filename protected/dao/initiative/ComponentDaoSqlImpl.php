<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\dao\initiative\ComponentDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\initiative\ActivityDaoSqlImpl;

/**
 * Description of ComponentDaoSqlImpl
 *
 * @author britech
 */
class ComponentDaoSqlImpl implements ComponentDao {

    private $db;
    private $activityDaoSource;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->activityDaoSource = new ActivityDaoSqlImpl();
    }

    public function addComponent(Component $component, Phase $phase) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO ini_components(component_desc, phase_ref) VALUES(:component, :phase)');
            $dbst->execute(array(
                'component' => $component->description,
                'phase' => $phase->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listComponents(Phase $phase) {
        try {
            $dbst = $this->db->prepare('SELECT component_id, component_desc FROM ini_components WHERE phase_ref=:ref');
            $dbst->execute(array('ref' => $phase->id));

            $components = array();
            while ($data = $dbst->fetch()) {
                $component = new Component();
                list($component->id, $component->description) = $data;
                $component->activities = $this->activityDaoSource->listActivities($component);
                array_push($components, $component);
            }
            return $components;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteComponent($id) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ini_components WHERE component_id=:id');
            $dbst->execute(array('id' => $id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateComponent(Component $component, Phase $phase) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE ini_components SET component_desc=:description, phase_ref=:phase WHERE component_id=:id');
            $dbst->execute(array(
                'description' => $component->description,
                'phase' => $phase->id,
                'id' => $component->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getComponentByActivity(Activity $activity) {
        try {
            $dbst = $this->db->prepare('SELECT component_ref FROM ini_activities WHERE activity_id=:id');
            $dbst->execute(array('id' => $activity->id));
            
            while($data = $dbst->fetch()){
                list($id) = $data;
            }
            return $this->getComponentByIdentifier($id);
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getComponentByIdentifier($id) {
        try {
            $dbst = $this->db->prepare('SELECT component_id, component_desc FROM ini_components WHERE component_id=:id');
            $dbst->execute(array('id' => $id));

            $component = new Component();
            while ($data = $dbst->fetch()) {
                list($component->id, $component->description) = $data;
            }
            return $component;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
