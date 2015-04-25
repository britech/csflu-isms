<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\dao\ubt\CommitmentUpdatesDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\CommitmentMovement;

/**
 * Description of CommitmentUpdatesDaoSqlImpl
 *
 * @author britech
 */
class CommitmentMovementDaoSqlImpl implements CommitmentUpdatesDao {

    private $db;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listMovements(Commitment $commitment) {
        try {
            $dbst = $this->db->prepare('SELECT figure, notes, date_entered FROM commitments_movements WHERE commit_ref=:ref');
            $dbst->execute(array('ref' => $commitment->id));

            $movements = array();
            while ($data = $dbst->fetch()) {
                $movement = new CommitmentMovement();
                list($movement->movementFigure, $movement->notes, $date) = $data;
                $movement->dateCaptured = \DateTime::createFromFormat('Y-m-d', $date);
            }
            return $movements;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
