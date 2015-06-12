<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\initiative\Activity;

/**
 * Description of Component
 *
 * @property String $id
 * @property String $description
 * @property Activity[] $activities
 * @author britech
 */
class Component extends Model {

    private $id;
    private $description;
    private $activities;
    
    public function __construct($description = null, $id = null) {
        if (!is_null($description)) {
            $this->description = $description;
        }

        if (!is_null($id)) {
            $this->id = $id;
        }
    }

    public function validate() {
        
    }

    public function isNew() {
        return empty($this->id);
    }

    public function computeTotalCompletionPercentage(\DateTime $period, $overall = false) {
        $percentage = 0.00;
        foreach ($this->activities as $activity) {
            $output = $activity->computeCompletionPercentage($period, $overall);
            $percentage+=is_numeric($output) ? $output : 0;
        }
        return $percentage;
    }

    public function computeTotalBudgetAllocation() {
        $budget = 0.00;
        foreach ($this->activities as $activity) {
            $budget += floatval($activity->budgetAmount);
        }
        return $budget;
    }

    public function resolveTotalBudgetAllocation() {
        $budget = $this->computeTotalBudgetAllocation();
        return $budget > 0 ? 'PHP ' . number_format($this->computeTotalBudgetAllocation()) : "-";
    }

    public function computeTotalRemainingBudget(\DateTime $period, $overall = false) {
        $utilized = 0.00;
        foreach ($this->activities as $activity) {
            $utilized += $activity->computeRemainingBudget($period, $overall);
        }
        return $utilized;
    }

    public function findUniqueActivityNumbers() {
        $activityNumbers = array();
        foreach ($this->activities as $activity) {
            if (!in_array($activity->activityNumber, $activityNumbers)) {
                $activityNumbers = array_merge($activityNumbers, array($activity->activityNumber));
            }
        }
        return $activityNumbers;
    }

    public function findActivitiesByActivityNumber($activityNumber) {
        $activities = array();
        foreach ($this->activities as $activity) {
            if ($activityNumber == $activity->activityNumber) {
                $activities = array_merge($activities, array($activity));
            }
        }
        return $activities;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $component = new Component();
        $component->id = $this->id;
        $component->description = $this->description;
    }

}
