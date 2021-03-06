<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
use org\csflu\isms\models\ubt\WigMeeting;

/**
 * Description of WigMeeting
 *
 * @property String $id
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property Commitment[] $commitments
 * @property UnitBreakthroughMovement[] $movementUpdates
 * @property WigMeeting $wigMeeting
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
    private $movementUpdates;
    private $wigMeeting;
    private $wigMeetingEnvironmentStatus = self::STATUS_OPEN;

    public static function listWigMeetingEnvironmentStatus() {
        return array(
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSED => 'Closed'
        );
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('wigMeeting', $valueArray)) {
            $this->wigMeeting = new WigMeeting();
            $this->wigMeeting->bindValuesUsingArray(array('wigmeeting' => $valueArray['wigMeeting']));
        }

        if (array_key_exists('wigsession', $valueArray)) {
            parent::bindValuesUsingArray($valueArray, $this);

            $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
            $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
        }
    }

    public function translateStatusCode($code = null) {
        $status = is_null($code) ? $this->wigMeetingEnvironmentStatus : strtoupper($code);
        if (!array_key_exists($status, self::listWigMeetingEnvironmentStatus())) {
            return "Undefined";
        } else {
            return self::listWigMeetingEnvironmentStatus()[$status];
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
            $translation.="Ending Date:\t{$this->endingPeriod->format('Y-m-d')}\n";
        }

        return $translation;
    }

    public function getClosedWigSessionLogOutput() {
        return "[WIG Session closed]\n\nTimeline:{$this->startingPeriod->format('M. j, Y')} - {$this->endingPeriod->format('M. j, Y')}";
    }

    public function getModelTranslationAsDeletedEntity() {
        return "[WigSession deleted]\n\nStarting Period:\t{$this->startingPeriod->format('Y-m-d')}\nEnding Period:\t{$this->endingPeriod->format('Y-m-d')}";
    }

    public function computeUbtMovement() {
        $value = 0.00;

        foreach ($this->movementUpdates as $movementUpdate) {
            $value += floatval($movementUpdate->ubtFigure);
        }

        return $value;
    }

    public function computeFirstLeadMeasureMovement() {
        $value = 0.00;

        foreach ($this->movementUpdates as $movementUpdate) {
            $value += floatval($movementUpdate->firstLeadMeasureFigure);
        }

        return $value;
    }

    public function computeSecondLeadMeasureMovement() {
        $value = 0.00;

        foreach ($this->movementUpdates as $movementUpdate) {
            $value += floatval($movementUpdate->secondLeadMeasureFigure);
        }

        return $value;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
