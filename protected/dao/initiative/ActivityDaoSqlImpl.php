<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\dao\initiative\ActivityDao;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;

/**
 * Description of ActivityDaoSqlImpl
 *
 * @author britech
 */
class ActivityDaoSqlImpl implements ActivityDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function addActivity(Activity $activity, Component $component) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('INSERT INTO ini_activities(component_ref, activity_desc, target_desc, target_figure, indicator, budget_figure, source, owners, period_start_date, period_end_date, activity_status) VALUES(:component, :activity, :target, :figure, :indicator, :budget, :source, :owners, :start, :end, :status)');
            $dbst->execute(array(
                'component' => $component->id,
                'activity' => $activity->title,
                'target' => $activity->descriptionOfTarget,
                'figure' => $activity->budgetAmount,
                'indicator' => $activity->indicator,
                'budget' => $activity->budgetAmount,
                'source' => $activity->sourceOfBudget,
                'owners' => $activity->owners,
                'start' => $activity->startingPeriod->format('Y-m-d'),
                'end' => $activity->endingPeriod->format('Y-m-d'),
                'status' => $activity->activityEnvironmentStatus
            ));

            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listActivities(Component $component) {
        try {
            $dbst = $this->db->prepare('SELECT activity_id, activity_desc FROM ini_activities WHERE component_ref=:component ORDER BY activity_desc ASC');
            $dbst->execute(array('component' => $component->id));

            $activities = array();
            while ($data = $dbst->fetch()) {
                $activity = new Activity();
                list($activity->id, $activity->title) = $data;
                array_push($activities, $activity);
            }
            return $activities;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
