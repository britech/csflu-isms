<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;

/**
 * Description of WigMeeting
 *
 * @property String $id
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property Commitment[] $commitments
 * @property UnitBreakthroughMovement $movementUpdate Description
 * @property String $wigMeetingEnvironmentStatus
 * @author britech
 */
class WigSession extends Model {

    const STATUS_OPEN = "O";
    const STATUS_CLOSED = "C";

    private $id;
    private $startingPeriod;
    private $endingPeriod;
    private $commitments;
    private $movementUpdate;
    private $wigMeetingEnvironmentStatus = self::STATUS_OPEN;

    public static function listWigMeetingEnvironmentStatus() {
        return array(
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSED => 'Closed'
        );
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);

        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function translateWigMeetingEnvironmentStatus() {
        if (!array_key_exists($this->wigMeetingEnvironmentStatus, self::listWigMeetingEnvironmentStatus())) {
            return "Undefined";
        } else {
            return self::listWigMeetingEnvironmentStatus()[$this->wigMeetingEnvironmentStatus];
        }
    }

    public function validate() {
        if (!($this->startingPeriod instanceof \DateTime or $this->endingPeriod instanceof \DateTime)) {
            array_push($this->validationMessages, 'Timeline should be defined');
        }
        return count($this->validationMessages) == 0;
    }

    public function getModelTranslationAsNewEntity() {
        return "[WIG Session enlisted]\n\nTimeline:\t{$this->startingPeriod->format('M. j, Y')} - {$this->endingPeriod->format('M. j, Y')}";
    }

    public function computePropertyChanges(WigSession $wigSession) {
        $counter = 0;

        if ($this->startingPeriod->format('Y-m-d') != $wigSession->startingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($this->endingPeriod->format('Y-m-d') != $wigSession->endingPeriod->format('Y-m-d')) {
            $counter++;
        }

        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(WigSession $wigSession) {
        $translation = "[WigSession updated]\n\n";
        if ($this->startingPeriod->format('Y-m-d') != $wigSession->startingPeriod->format('Y-m-d')) {
            $translation.="Starting Date:\t{$this->startingPeriod->format('Y-m-d')}";
        }

        if ($this->endingPeriod->format('Y-m-d') != $wigSession->endingPeriod->format('Y-m-d')) {
            $translation.="Ending Date:\t{$this->endingPeriod->format('Y-m-d')}";
        }
        return $translation;
    }
    
    public function getModelTranslationAsDeletedEntity() {
        return "[WigSession deleted]\n\nStarting Period:\t{$this->startingPeriod->format('Y-m-d')}\nEnding Period:\t{$this->endingPeriod->format('Y-m-d')}";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
