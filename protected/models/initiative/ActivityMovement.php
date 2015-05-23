<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\UserAccount;

/**
 * Description of ActivityMovement
 *
 * @property UserAccount $user
 * @property String $actualFigure
 * @property String $budgetAmount
 * @property \DateTime $periodDate
 * @property \DateTime $movementTimestamp
 * @property String $notes
 * @author britech
 */
class ActivityMovement extends Model {

    private $user;
    private $actualFigure;
    private $budgetAmount;
    private $periodDate;
    private $movementTimestamp;
    private $notes;

    public function validate() {
        if (strlen($this->notes) < 1) {
            array_push($this->validationMessages, '- Notes should be defined');
        }

        if (!$this->periodDate instanceof \DateTime) {
            array_push($this->validationMessages, '- Covered Period should be defined');
        }

        if (strlen($this->actualFigure) > 1 && !is_numeric($this->actualFigure)) {
            array_push($this->validationMessages, '- Actual Figure should be in numerical representation');
        }

        if (strlen($this->budgetAmount) > 1 && !is_numeric($this->budgetAmount)) {
            array_push($this->validationMessages, '- Budget Amount should be in numerical representation');
        }

        return count($this->validationMessages) == 0;
    }

    public function getAttributeNames() {
        return array(
            'actualFigure' => 'Output',
            'budgetAmount' => 'Budget Spent',
            'notes' => 'Notes'
        );
    }

    public function getModelTranslationAsNewEntity() {
        return "[Movement added]\n\n"
                . "Period Covered:\t{$this->periodDate->format('F Y')}\n"
                . "Output:\t{$this->actualFigure}\n"
                . "Budget Spent:\t{$this->budgetAmount}\n"
                . "Notes:\t{$this->notes}";
    }
    
    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);
        $this->periodDate = \DateTime::createFromFormat('Y-m-d', $this->periodDate);
    }

    public function retrieveName() {
        $firstName = substr($this->user->employee->givenName, 0, 1);
        return "{$firstName}. {$this->user->employee->lastName}";
    }

    public function resolveOutputValue() {
        return is_null($this->actualFigure) || strlen($this->actualFigure) < 1 ? "-" : number_format(floatval($this->actualFigure), 2);
    }

    public function resolveBudgetValue() {
        return is_null($this->budgetAmount) || strlen($this->budgetAmount) < 1 || empty(floatval($this->budgetAmount)) ? "-" : "PHP " . number_format(floatval($this->budgetAmount), 2);
    }

    public function constructNotes() {
        return nl2br(implode("\n", explode("+", $this->notes)));
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
