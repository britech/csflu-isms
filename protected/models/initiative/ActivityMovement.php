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
 * @property \DateTime $movementTimestamp
 * @property String $notes
 * @author britech
 */
class ActivityMovement extends Model {

    private $user;
    private $actualFigure;
    private $budgetAmount;
    private $movementTimestamp;
    private $notes;

    public function validate() {
        if (strlen($this->notes) < 1) {
            array_push($this->validationMessages, '- Notes should be defined');
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

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
