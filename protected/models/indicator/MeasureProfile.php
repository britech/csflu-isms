<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\Target;

/**
 * 
 * @property String $id
 * @property Objective $objective
 * @property Indicator $indicator
 * @property String $measureType
 * @property String $frequencyOfMeasure
 * @property LeadOffice[] $leadOffices
 * @property Target[] $targets
 * @property String $measureProfileEnvironmentStatus
 * @property \DateTime $timelineStart
 * @property \DateTime $timelineEnd
 * @author britech
 */
class MeasureProfile extends Model {

    const TYPE_LEAD = "LD";
    const TYPE_LAG = "LG";
    const FREQUENCY_DAILY = "D";
    const FREQUENCY_WEEKLY = "W";
    const FREQUENCY_MONTHLY = "M";
    const FREQUENCY_QUARTER = "Q";
    const FREQUENCY_SEMESTER = "S";
    const FREQUENCY_ANNUAL = "A";
    const STATUS_ACTIVE = "A";
    const STATUS_DROPPED = "D";

    private $id;
    private $objective;
    private $indicator;
    private $measureType;
    private $frequencyOfMeasure;
    private $leadOffices;
    private $targets;
    private $measureProfileEnvironmentStatus;
    private $timelineStart;
    private $timelineEnd;
    public $periods;

    public function validate() {
        $counter = 0;

        if (strlen($this->objective->id) == 0) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['objective'] . ' should be defined');
            $counter++;
        }

        if (strlen($this->indicator->id) == 0) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['indicator'] . ' should be defined');
            $counter++;
        }

        if (empty($this->measureType)) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['measureType'] . ' should be defined');
            $counter++;
        }

        if (empty($this->frequencyOfMeasure)) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['frequencyOfMeasure'] . ' should be defined');
            $counter++;
        } else {
            $counter = $this->validateFrequencyOfMeasureInput($counter);
        }

        if (empty($this->measureProfileEnvironmentStatus)) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['measureProfileEnvironmentStatus'] . ' should be defined');
            $counter++;
        }

        if (empty($this->timelineStart) && empty($this->timelineEnd)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['periods']} should be defined");
            $counter++;
        }

        return $counter == 0;
    }

    private function validateFrequencyOfMeasureInput($counter) {
        $input = explode($this->arrayDelimiter, $this->frequencyOfMeasure);
        $valid = 0;
        for ($i = 0; $i < count($input); $i++) {
            if (array_key_exists($input[$i], self::getFrequencyTypes())) {
                $valid++;
            }
        }

        if ($valid != count($input)) {
            array_push($this->validationMessages, '- ' . $this->getAttributeNames()['frequencyOfMeasure'] . ' is invalid');
            $counter++;
        }

        return $counter;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('objective', $valueArray)) {
            $this->objective = new Objective();
            $this->objective->bindValuesUsingArray($valueArray);
        }

        if (array_key_exists('indicator', $valueArray)) {
            $this->indicator = new Indicator();
            $this->indicator->bindValuesUsingArray($valueArray);
        }

        parent::bindValuesUsingArray($valueArray, $this);

        if (!empty($this->timelineStart)) {
            $this->timelineStart = \DateTime::createFromFormat('Y-m-d', $this->timelineStart);
        }

        if (!empty($this->timelineEnd)) {
            $this->timelineEnd = \DateTime::createFromFormat('Y-m-d', $this->timelineEnd);
        }
    }

    public static function getMeasureTypes() {
        return array(self::TYPE_LEAD => 'Lead Measure',
            self::TYPE_LAG => 'Lag Measure');
    }

    public static function getFrequencyTypes() {
        return array(self::FREQUENCY_DAILY => 'Daily',
            self::FREQUENCY_WEEKLY => 'Weekly',
            self::FREQUENCY_MONTHLY => 'Monthly',
            self::FREQUENCY_QUARTER => 'Quarterly',
            self::FREQUENCY_SEMESTER => 'Semesteral',
            self::FREQUENCY_ANNUAL => 'Annually');
    }

    public static function getEnvironmentStatusTypes() {
        return array(self::STATUS_ACTIVE => 'Active',
            self::STATUS_DROPPED => 'Dropped');
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getAttributeNames() {
        return array('objective' => 'Objective',
            'indicator' => 'Indicator',
            'measureType' => 'Type of Measure',
            'frequencyOfMeasure' => 'Frequency of Measure to be Updated',
            'leadOffices' => 'Responsibility Center',
            'targets' => 'Targets',
            'measureProfileEnvironmentStatus' => 'Status',
            'periods' => 'Timeline');
    }

    public function getModelTranslationAsNewEntity() {
        $frequencyInputs = explode($this->arrayDelimiter, $this->frequencyOfMeasure);
        $frequencyValues = array();
        foreach ($frequencyInputs as $input) {
            array_push($frequencyValues, self::getFrequencyTypes()[$input]);
        }

        return "[Measure Profile added]\n\n"
                . "Objective:\t{$this->objective->description}\n"
                . "Indicator:\t{$this->indicator->description}\n"
                . "Measure Type:\t{$this->getMeasureTypes()[$this->measureType]}\n"
                . "Frequency:\t" . implode($this->arrayDelimiter, $frequencyValues) . "\n"
                . "Status:\t{$this->getEnvironmentStatusTypes()[$this->measureProfileEnvironmentStatus]}\n"
                . "Timeline:\t{$this->timelineStart->format('F-Y')} - {$this->timelineEnd->format('F-Y')}";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
