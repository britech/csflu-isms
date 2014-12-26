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

    public function validate() {
        
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

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
