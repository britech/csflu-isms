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
                'figure' => $activity->targetFigure,
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
            $dbst = $this->db->prepare('SELECT activity_id FROM ini_activities WHERE component_ref=:component ORDER BY period_start_date ASC');
            $dbst->execute(array('component' => $component->id));

            $activities = array();
            while ($data = $dbst->fetch()) {
                list($id) = $data;
                array_push($activities, $this->getActivity($id));
            }
            return $activities;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function getActivity($id) {
        try {
            $dbst = $this->db->prepare('SELECT activity_id, activity_desc, target_desc, target_figure, indicator, budget_figure, source, owners, period_start_date, period_end_date, activity_status FROM ini_activities WHERE activity_id=:id');
            $dbst->execute(array('id' => $id));

            $activity = new Activity();
            while ($data = $dbst->fetch()) {
                list($activity->id,
                        $activity->title,
                        $activity->descriptionOfTarget,
                        $activity->targetFigure,
                        $activity->indicator,
                        $activity->budgetAmount,
                        $activity->sourceOfBudget,
                        $activity->owners,
                        $activity->startingPeriod,
                        $activity->endingPeriod,
                        $activity->activityEnvironmentStatus) = $data;
            }
            $activity->startingPeriod = \DateTime::createFromFormat('Y-m-d', $activity->startingPeriod);
            $activity->endingPeriod = \DateTime::createFromFormat('Y-m-d', $activity->endingPeriod);

            return $activity;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateActivity(Activity $activity, Component $component) {
        try {
            $this->db->beginTransaction();

            $dbst = $this->db->prepare('UPDATE ini_activities SET activity_desc=:description, '
                    . 'target_desc=:targetDescription, '
                    . 'target_figure=:targetFigure, '
                    . 'indicator=:indicator, '
                    . 'budget_figure=:budget, '
                    . 'source=:source, '
                    . 'owners=:owners, '
                    . 'period_start_date=:start, '
                    . 'period_end_date=:end, '
                    . 'component_ref=:component '
                    . 'WHERE activity_id=:id');

            $dbst->execute(array(
                'description' => $activity->title,
                'targetDescription' => $activity->descriptionOfTarget,
                'targetFigure' => $activity->targetFigure,
                'indicator' => $activity->indicator,
                'budget' => $activity->budgetAmount,
                'source' => $activity->sourceOfBudget,
                'owners' => $activity->owners,
                'start' => $activity->startingPeriod->format('Y-m-d'),
                'end' => $activity->endingPeriod->format('Y-m-d'),
                'component' => $component->id,
                'id' => $activity->id
            ));
            $this->db->commit();
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
