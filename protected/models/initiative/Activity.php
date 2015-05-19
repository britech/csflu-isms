<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\initiative\ActivityMovement;

/**
 * Description of Activity
 *
 * @property String $id
 * @property String $activityNumber
 * @property String $title
 * @property String $descriptionOfTarget
 * @property String $targetFigure
 * @property String $indicator
 * @property String $budgetAmount
 * @property String $sourceOfBudget
 * @property String $owners
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property ActivityMovement[] $movements
 * @property String $activityEnvironmentStatus
 * @author britech
 */
class Activity extends Model {

    const STATUS_PENDING = "P";
    const STATUS_ONGOING = "A";
    const STATUS_FINISHED = "C";
    const STATUS_DROPPED = "D";
    const STATUS_UNFINISHED = "I";

    private $id;
    private $activityNumber;
    private $title;
    private $descriptionOfTarget;
    private $targetFigure;
    private $indicator;
    private $budgetAmount;
    private $sourceOfBudget;
    private $owners;
    private $startingPeriod;
    private $endingPeriod;
    private $movements;
    private $activityEnvironmentStatus = self::STATUS_PENDING;

    public static function listEnvironmentStatusCodes() {
        return array(
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_FINISHED => 'Finished',
            self::STATUS_DROPPED => 'Discontinued',
            self::STATUS_UNFINISHED => 'Unfinished'
        );
    }

    public function translateStatusCode($code = null) {
        $statusCode = is_null($code) ? $this->activityEnvironmentStatus : $code;
        if (array_key_exists($statusCode, self::listEnvironmentStatusCodes())) {
            return self::listEnvironmentStatusCodes()[$statusCode];
        }
        return 'Undefined';
    }

    public function validate() {
        if (strlen($this->activityNumber) < 1) {
            array_push($this->validationMessages, '- Activity Number should be defined');
        }

        if (strlen($this->title) < 1) {
            array_push($this->validationMessages, '- Activity should be defined');
        }

        if (strlen($this->descriptionOfTarget) < 1 && strlen($this->targetFigure) < 1) {
            array_push($this->validationMessages, '- Either target in descriptive or numerical representation must be defined');
        } elseif (strlen($this->descriptionOfTarget) < 1) {
            array_push($this->validationMessages, '- Target in descriptive representation must be defined');
        }

        if (strlen($this->targetFigure) > 1 && !is_numeric($this->targetFigure)) {
            array_push($this->validationMessages, '- Target in numerical representation must be in numbers.');
        }

        if (strlen($this->indicator) < 1) {
            array_push($this->validationMessages, '- Indicator should be defined');
        }

        if (strlen($this->owners) < 1) {
            array_push($this->validationMessages, '- Implementing Entities should be defined');
        }

        if (!empty($this->budgetAmount) && !is_numeric($this->budgetAmount)) {
            array_push($this->validationMessages, '- Budget Amount should be in numerical representation');
        }

        if (is_numeric($this->budgetAmount) && !empty(floatval($this->budgetAmount)) && strlen($this->sourceOfBudget) < 1) {
            array_push($this->validationMessages, '- Source of Budget must be defined');
        }

        if (!$this->startingPeriod instanceof \DateTime || !$this->endingPeriod instanceof \DateTime) {
            array_push($this->validationMessages, '- Timeline should be defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);
        $this->activityNumber = strtoupper($this->activityNumber);
        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function getAttributeNames() {
        return array(
            'activityNumber' => 'Activity Number',
            'title' => 'Activity',
            'descriptionOfTarget' => 'Target (in description)',
            'targetFigure' => 'Target (in numerical representation)',
            'indicator' => 'Indicator',
            'budgetAmount' => 'Budget',
            'sourceOfBudget' => 'Source of Budget',
            'owners' => 'Implementing Entities'
        );
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getModelTranslationAsNewEntity() {
        return "[Activity added]\n\n"
                . "Number:\t{$this->activityNumber}\n"
                . "Activity:\t{$this->title}\n"
                . "Target Definition:\t{$this->descriptionOfTarget}\n"
                . "Indicator:\t{$this->indicator}\n"
                . "Budget:\t{$this->budgetAmount} ({$this->sourceOfBudget})\n"
                . "Implementing Entities:\t{$this->owners}\n"
                . "Timeline:\t{$this->startingPeriod->format('F-Y')} - {$this->endingPeriod->format('F-Y')}";
    }

    public function computePropertyChanges(Activity $oldModel) {
        $counter = 0;

        if ($this->activityNumber != $oldModel->activityNumber) {
            $counter++;
        }

        if ($this->title != $oldModel->title) {
            $counter++;
        }

        if ($this->descriptionOfTarget != $oldModel->descriptionOfTarget) {
            $counter++;
        }

        if ($this->targetFigure != $oldModel->targetFigure) {
            $counter++;
        }

        if ($this->indicator != $oldModel->indicator) {
            $counter++;
        }

        if ($this->owners != $oldModel->owners) {
            $counter++;
        }

        if ($this->startingPeriod->format('Y-m-d') != $oldModel->startingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($this->endingPeriod->format('Y-m-d') != $oldModel->endingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($this->budgetAmount != $oldModel->budgetAmount) {
            $counter++;
        }

        if ($this->sourceOfBudget != $oldModel->sourceOfBudget) {
            $counter++;
        }

        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(Activity $oldModel) {
        $translation = "[Activity updated]\n\n";

        if ($this->activityNumber != $oldModel->activityNumber) {
            $translation.="Activity Number:\t{$this->activityNumber}";
        }

        if ($this->title != $oldModel->title) {
            $translation.="Activity:\t{$this->title}\n";
        }

        if ($this->descriptionOfTarget != $oldModel->descriptionOfTarget) {
            $translation.="Description of Target:\t{$this->descriptionOfTarget}\n";
        }

        if ($this->targetFigure != $oldModel->targetFigure) {
            $translation.="Target Figure:\t{$this->targetFigure}\n";
        }

        if ($this->indicator != $oldModel->indicator) {
            $translation.="Indicator:\t{$this->indicator}\n";
        }

        if ($this->owners != $oldModel->owners) {
            $translation.="Implementing Entities:\t{$this->owners}\n";
        }

        if ($this->startingPeriod->format('Y-m-d') != $oldModel->startingPeriod->format('Y-m-d')) {
            $translation.="Starting Period:\t{$this->startingPeriod->format('F-Y')}\n";
        }

        if ($this->endingPeriod->format('Y-m-d') != $oldModel->endingPeriod->format('Y-m-d')) {
            $translation.="Ending Period:\t{$this->endingPeriod->format('F-Y')}\n";
        }

        if ($this->budgetAmount != $oldModel->budgetAmount) {
            $translation.="Budget Amount:\t{$this->budgetAmount}\n";
        }

        if ($this->sourceOfBudget != $oldModel->sourceOfBudget) {
            $translation.="Source of Budget:\t{$this->sourceOfBudget}";
        }

        return $translation;
    }

    public function getModelTranslationAsDeletedEntity() {
        return "[Activity deleted]\n\n"
                . "Number:\t{$this->activityNumber}\n"
                . "Activity:\t{$this->title}\n";
    }

    public function computeRemainingBudget() {
        $budget = 0.00;
        foreach ($this->movements as $movement) {
            $budget+=floatval($movement->budgetAmount);
        }
        return floatval($this->budgetAmount) - $budget;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $activity = new Activity();
        $activity->id = $this->id;
        $activity->activityNumber = $this->activityNumber;
        $activity->descriptionOfTarget = $this->descriptionOfTarget;
        $activity->targetFigure = $this->targetFigure;
        $activity->indicator = $this->indicator;
        $activity->owners = $this->owners;
        $activity->startingPeriod = $this->startingPeriod;
        $activity->endingPeriod = $this->endingPeriod;
        $activity->budgetAmount = $this->budgetAmount;
        $activity->sourceOfBudget = $this->sourceOfBudget;
    }

}
