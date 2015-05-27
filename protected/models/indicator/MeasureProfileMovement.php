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

    public function getModelTranslationAsNewEntity() {
        $logData = "";
        foreach ($this->movementLogs as $movementLog) {
            $logData.="{$movementLog->getModelTranslationAsNewEntity()}";
        }

        return "[Movement added]\n\n"
                . "Period:\t{$this->periodDate->format('M Y')}"
                . "Value:\t{$this->movementValue}\n\n"
                . "==========[Logs]\n{$logData}\n==========";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
