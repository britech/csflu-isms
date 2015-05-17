<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\dao\initiative\ActivityMovementDao;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\initiative\ActivityMovement;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\uam\UserManagementDaoSqlImpl;

/**
 * Description of ActivityMovementDaoSqlImpl
 *
 * @author britech
 */
class ActivityMovementDaoSqlImpl implements ActivityMovementDao {

    private $db;
    private $userDao;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->userDao = new UserManagementDaoSqlImpl();
    }

    public function listMovements(Activity $activity) {
        try {
            $dbst = $this->db->prepare('SELECT user_ref, actual_figure, budget_amount, movement_timestamp, notes FROM ini_movement WHERE activity_ref=:ref');
            $dbst->execute(array('ref' => $activity->id));

            $movements = array();
            while ($data = $dbst->fetch()) {
                $movement = new ActivityMovement();
                list($user, $movement->actualFigure, $movement->budgetAmount, $date, $movement->notes) = $data;
                $movement->user = $this->userDao->getUserAccount($user);
                $movement->movementTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
                $movements = array_merge($movements, array($movement));
            }
            return $movements;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function recordMovements(Activity $activity) {
        try {
            $this->db->beginTransaction();
            foreach ($activity->movements as $movement) {
                $dbst = $this->db->prepare('INSERT INTO ini_movement(activity_ref, user_ref, actual_figure, budget_amount, notes) VALUES(:activity, :user, :figure, :budget, :notes)');
                $dbst->execute(array(
                    'activity' => $activity->id,
                    'user' => $movement->user->id,
                    'figure' => $movement->actualFigure,
                    'budget' => $movement->budgetAmount,
                    'notes' => $movement->notes
                ));
            }
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
