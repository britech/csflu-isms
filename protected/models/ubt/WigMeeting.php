<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of WigMeeting
 * 
 * @property \DateTime $actualSessionStartDate
 * @property \DateTime $actualSessionEndDate
 * @property String $meetingVenue
 * @property \DateTime $meetingDate
 * @property \DateTime $meetingTimeStart
 * @property \DateTime $meetingTimeEnd
 * @author britech
 */
class WigMeeting extends Model {

    private $actualSessionStartDate;
    private $actualSessionEndDate;
    private $meetingVenue;
    private $meetingDate;
    private $meetingTimeStart;
    private $meetingTimeEnd;
    
    public function validate() {
        if (!($this->actualSessionStartDate instanceof \DateTime || $this->actualSessionEndDate instanceof \DateTime)) {
            array_push($this->validationMessages, '- Actual WIG Timeline should be defined');
        }

        if (strlen($this->meetingVenue) < 1) {
            array_push($this->validationMessages, '- Meeting Venue should be defined');
        }

        if (!($this->meetingTimeStart instanceof \DateTime || $this->meetingTimeEnd instanceof \DateTime)) {
            array_push($this->validationMessages, '- Meeting Time should defined');
        } elseif(($this->meetingTimeStart instanceof \DateTime && $this->meetingTimeStart instanceof \DateTime) && $this->meetingTimeEnd < $this->meetingTimeStart){
            array_push($this->validationMessages, '- Time Start is greater than the Time End');
        }

        if (!$this->meetingDate instanceof \DateTime) {
            array_push($this->validationMessages, '- Meeting Date should be defined');
        }
        
        return count($this->validationMessages) == 0;
    }

    public function getAttributeNames() {
        return array(
            'actualSessionStartDate' => 'Actual Start Date',
            'actualSessionEndDate' => 'Actual End Date',
            'meetingVenue' => 'Meeting Venue',
            'meetingDate' => 'Meeting Date',
            'meetingTimeStart' => 'Time Start',
            'meetingTimeEnd' => 'Time End'
        );
    }

    public function bindValuesUsingArray(array $valueArray) {
        parent::bindValuesUsingArray($valueArray, $this);
        
        $this->actualSessionStartDate = \DateTime::createFromFormat('Y-m-d', $this->actualSessionStartDate);
        $this->actualSessionEndDate = \DateTime::createFromFormat('Y-m-d', $this->actualSessionEndDate);
        $this->meetingDate = \DateTime::createFromFormat('Y-m-d', $this->meetingDate);
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
