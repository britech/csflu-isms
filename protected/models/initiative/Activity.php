<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;

/**
 * Description of Activity
 *
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
    private $budgetAmount;
    private $sourceOfBudget;
    private $startingPeriod;
    private $endingPeriod;
    private $activityEnvironmentStatus = self::STATUS_PENDING;

    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
