<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\dao\ubt\CommitmentMovementDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\CommitmentMovement;

/**
 * Description of CommitmentUpdatesDaoSqlImpl
 *
 * @author britech
 */
class CommitmentMovementDaoSqlImpl implements CommitmentMovementDao {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMovements(Commitment $commitment) {
        try {
            $dbst = $this->db->prepare('SELECT figure, notes, date_entered FROM commitments_movement WHERE commit_ref=:ref');
            $dbst->execute(array('ref' => $commitment->id));

            $movements = array();
            while ($data = $dbst->fetch()) {
                $movement = new CommitmentMovement();
                list($movement->movementFigure, $movement->notes, $date) = $data;
                $movement->dateCaptured = \DateTime::createFromFormat('Y-m-d', $date);
                $movements = array_merge($movements, array($movement));
            }
            return $movements;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function addMovementUpdates(Commitment $commitment) {
        try {
            $this->db->beginTransaction();

            foreach ($commitment->commitmentMovements as $commitmentMovement) {
                $dbst = $this->db->prepare('INSERT INTO commitments_movement(commit_ref, figure, notes) VALUES(:commit, :figure, :notes)');
                $dbst->execute(array(
                    'commit' => $commitment->id,
                    'figure' => $commitmentMovement->movementFigure,
                    'notes' => $commitmentMovement->notes
                ));
            }

            $dbst = $this->db->prepare('UPDATE commitments_main SET status=:status WHERE commit_id=:id');
            $dbst->execute(array(
                'status' => $commitment->commitmentEnvironmentStatus,
                'id' => $commitment->id
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
