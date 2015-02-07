<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\dao\initiative\ComponentDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\exceptions\DataAccessException;

/**
 * Description of ComponentDaoSqlImpl
 *
 * @author britech
 */
class ComponentDaoSqlImpl implements ComponentDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
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
                array_push($components, $component);
            }
            return $components;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
