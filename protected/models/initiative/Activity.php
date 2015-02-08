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

        if (strlen($this->title) < 1) {
            array_push($this->validationMessages, '- Activity should be defined');
        }

        if (strlen($this->descriptionOfTarget) < 1 && strlen($this->targetFigure) < 1) {
            array_push($this->validationMessages, '- Either target in descriptive or numerical representation must be defined');
        } elseif (strlen($this->descriptionOfTarget) < 1) {
            array_push($this->validationMessages, '- Target in descriptive representation must be defined');
        }

        if (strlen($this->indicator) < 1) {
            array_push($this->validationMessages, '- Indicator should be defined');
        }

        if (strlen($this->budgetAmount) > 1 && !is_numeric($this->budgetAmount)) {
            array_push($this->validationMessages, '- Budget Amount should be in numerical representation');
        }

        if (strlen($this->budgetAmount) > 1 && strlen($this->sourceOfBudget) < 1) {
            array_push($this->validationMessages, '- Source of Budget must be defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);
        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
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
