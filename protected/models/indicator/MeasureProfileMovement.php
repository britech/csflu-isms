<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * Description of MeasureProfileMovement
 *
 * @property \DateTime $periodDate
 * @property string $movementValue
 * @property MeasureProfileMovementLog[] $movementLogs
 * @author britech
 */
class MeasureProfileMovement extends Model {

    private $periodDate;
    private $movementValue;
    private $movementLogs;

    public function validate() {
        if (!$this->periodDate instanceof \DateTime) {
            array_push($this->validationMessages, '- Time Period should be defined');
        }

        if (!strlen($this->movementValue) > 0) {
            array_push($this->validationMessages, '- Movement value should be defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);
        $this->periodDate = \DateTime::createFromFormat('Y-m-d', $this->periodDate);
    }

    public function getAttributeNames() {
        return array(
            'periodDate' => 'Time Period',
            'movementValue' => 'Movement Value'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
