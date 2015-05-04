<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of WigMeeting
 * 
 * @property \DateTime $actualSessionStartDate
 * @property \DateTime $actualSessionEndDate
 * @property String $meetingVenue
 * @property \DateTime $meetingTimeStart
 * @property \DateTime $meetingTimeEnd
 * @author britech
 */
class WigMeeting extends Model {

    private $actualSessionStartDate;
    private $actualSessionEndDate;
    private $meetingVenue;
    private $meetingTimeStart;
    private $meetingTimeEnd;

    public function validate() {
        if (!($this->actualSessionStartDate instanceof \DateTime || $this->actualSessionEndDate instanceof \DateTime)) {
            array_push($this->validationMessages, '- Actual Start and End Dates should be defined');
        }

        if (strlen($this->meetingVenue) > 1) {
            array_push($this->validationMessages, '- Meeting Venue should be defined');
        }

        if (!($this->meetingTimeStart instanceof \DateTime || $this->meetingTimeEnd instanceof \DateTime)) {
            array_push($this->validationMessages, '- Meeting Time should defined');
        }
    }

    public function getAttributeNames() {
        return array(
            'actualSessionStartDate' => 'Actual Start Date',
            'actualSessionEndDate' => 'Actual End Date',
            'meetingVenue' => 'Venue',
            'meetingTimeStart' => 'Time Start',
            'meetingTimeEnd' => 'Time End'
        );
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);

        $this->actualSessionStartDate = \DateTime::createFromFormat('Y-m-d', $this->actualSessionStartDate);
        $this->actualSessionEndDate = \DateTime::createFromFormat('Y-m-d', $this->actualSessionEndDate);
        $this->meetingTimeStart = \DateTime::createFromFormat('H:i:s', $this->meetingTimeStart);
        $this->meetingTimeEnd = \DateTime::createFromFormat('H:i:s', $this->meetingTimeEnd);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
