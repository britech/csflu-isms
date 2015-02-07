<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\initiative\PhaseDao;

/**
 * Description of PhaseDaoSqlImpl
 *
 * @author britech
 */
class PhaseDaoSqlImpl implements PhaseDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function addPhase(Phase $phase, Initiative $initiative) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO ini_phases(phase_number, phase_title, phase_desc, ini_ref) VALUES(:phaseNumber, :title, :description, :initiative)');
            $dbst->execute(array(
                'phaseNumber' => $phase->phaseNumber,
                'title' => $phase->title,
                'description' => $phase->description,
                'initiative' => $initiative->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listPhases(Initiative $initiative) {
        try {
            $dbst = $this->db->prepare('SELECT phase_id, phase_number, phase_title, phase_desc FROM ini_phases WHERE ini_ref=:initiative ORDER BY phase_number ASC');
            $dbst->execute(array(
                'initiative' => $initiative->id
            ));

            $phases = array();
            while ($data = $dbst->fetch()) {
                $phase = new Phase();
                list($phase->id, $phase->phaseNumber, $phase->title, $phase->description) = $data;
                array_push($phases, $phase);
            }
            return $phases;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updatePhase(Phase $phase) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE ini_phases SET phase_number=:number, phase_title=:title, phase_desc=:description WHERE phase_id=:id');
            $dbst->execute(array(
                'number' => $phase->phaseNumber,
                'title' => $phase->title,
                'description' => $phase->description,
                'id' => $phase->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deletePhase($id) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('DELETE FROM ini_phases WHERE phase_id=:id');
            $dbst->execute(array('id' => $id));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
