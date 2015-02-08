<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;

/**
 * Description of Activity
 *
 * @property String $id
 * @property String $title
 * @property String $descriptionOfTarget
 * @property String $targetFigure
 * @property String $indicator
 * @property String $budgetAmount
 * @property String $sourceOfBudget
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property String $activityEnvironmentStatus
 * @author britech
 */
class Activity extends Model {

    const STATUS_PENDING = "P";
    const STATUS_ONGOING = "A";
    const STATUS_FINISHED = "C";
    const STATUS_STALLED = "S";
    const STATUS_DROPPED = "D";

    private $id;
    private $title;
    private $descriptionOfTarget;
    private $targetFigure;
    private $indicator;
    private $budgetAmount;
    private $sourceOfBudget;
    private $startingPeriod;
    private $endingPeriod;
    private $activityEnvironmentStatus = self::STATUS_PENDING;

    public function validate() {
        
    }

    public function getAttributeNames() {
        return array(
            'title' => 'Activity',
            'descriptionOfTarget' => 'Target (in description)',
            'targetFigure' => 'Target (in numerical representation)',
            'indicator' => 'Indicator',
            'budgetAmount' => 'Budget',
            'sourceOfBudget' => 'Source of Budget'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
