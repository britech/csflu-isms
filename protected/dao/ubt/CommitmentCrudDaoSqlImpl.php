<?php

namespace org\csflu\isms\dao\ubt;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\ubt\CommitmentCrudDao;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\ubt\Commitment;

/**
 * Description of CommitmentCrudDaoSqlImpl
 *
 * @author britech
 */
class CommitmentCrudDaoSqlImpl implements CommitmentCrudDao {

    private $db;
    private $userDao;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->userDao = new UserManagementDaoSqlImpl();
    }

    public function insertCommitments(WigSession $wigSession) {
        try {
            $this->db->beginTransaction();

            foreach ($wigSession->commitments as $commitment) {
                $dbst = $this->db->prepare('INSERT INTO commitments_main(user_ref, wig_ref, commit_description, status) VALUES(:user, :wig, :description, :status)');
                $dbst->execute(array(
                    'user' => $commitment->user->id,
                    'wig' => $wigSession->id,
                    'description' => $commitment->commitment,
                    'status' => $commitment->commitmentEnvironmentStatus
                ));
            }
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listCommitments(WigSession $wigSession) {
        try {
            $dbst = $this->db->prepare('SELECT commit_id FROM commitments_main WHERE wig_ref=:ref');
            $dbst->execute(array(
                'ref' => $wigSession->id
            ));

            $commitments = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                $commitments = array_merge($commitments, array($this->getCommitmentData($id)));
            }
            return $commitments;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getCommitmentData($id) {
        try {
            $dbst = $this->db->prepare('SELECT commit_id, user_ref, commit_description, status FROM commitments_main WHERE commit_id=:id');
            $dbst->execute(array(
                'id' => $id
            ));

            $commitment = new Commitment();
            while ($data = $dbst->fetch()) {
                list($commitment->id,
                        $user,
                        $commitment->commitment,
                        $commitment->commitmentEnvironmentStatus) = $data;
            }
            $commitment->user = $this->userDao->getUserAccount($user);
            return $commitment;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateCommitmentData(Commitment $commitment) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE commitments_main SET commit_description=:description, status=:status WHERE commit_id=:id');
            $dbst->execute(array(
                'description' => $commitment->commitment,
                'status' => $commitment->commitmentEnvironmentStatus
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
