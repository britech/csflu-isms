<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\commons\Department;

/**
 * Description of Initiative
 *
 * @property String $id
 * @property String $title
 * @property String $description
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property String $eoNumber
 * @property String $advisers
 * @property Objective[] $objectives
 * @property MeasureProfile[] $leadMeasures
 * @property ImplementingOffice[] $implementingOffices
 * @property Phase[] $phases
 * @property String $initiativeEnvironmentStatus
 * @author britech
 */
class Initiative extends Model {

    const STATUS_ACTIVE = "A";
    const STATUS_INACTIVE = "I";
    const STATUS_COMPLETED = "C";
    const STATUS_TERMINATED = "T";

    private $id;
    private $title;
    private $description;
    private $beneficiaries;
    private $startingPeriod;
    private $endingPeriod;
    private $eoNumber;
    private $advisers;
    private $objectives;
    private $leadMeasures;
    private $implementingOffices;
    private $phases;
    private $initiativeEnvironmentStatus = self::STATUS_ACTIVE;

    public static function getEnvironmentStatusTypes() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_TERMINATED => 'Terminated'
        );
    }

    public function validate() {
        if (empty($this->title)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['title']} should be defined");
        }

        if (empty($this->description)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['description']} should be defined");
        }
        
        if(empty($this->beneficiaries)){
            array_push($this->validationMessages, "- {$this->getAttributeNames()['beneficiaries']} should be defined");
        }

        if ($this->validationMode == self::VALIDATION_MODE_INITIAL) {
            if (count($this->objectives) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['objectives']} should be defined");
            }

            if (count($this->leadMeasures) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['leadMeasures']} should be defined");
            }

            if (count($this->implementingOffices) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['implementingOffices']} should be defined");
            }
        }
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('objectives', $valueArray) && !empty($valueArray['objectives']['id'])) {
            $objectives = explode("/", $valueArray['objectives']['id']);
            $data = array();
            foreach ($objectives as $id) {
                $objective = new Objective();
                $objective->id = $id;
                array_push($data, $objective);
            }
            $this->objectives = $data;
        }

        if (array_key_exists('leadMeasures', $valueArray) && !empty($valueArray['leadMeasures']['id'])) {
            $leadMeasures = explode("/", $valueArray['leadMeasures']['id']);
            $data = array();
            foreach ($leadMeasures as $id) {
                $leadMeasure = new MeasureProfile();
                $leadMeasure->id = $id;
                array_push($data, $leadMeasure);
            }
            $this->leadMeasures = $data;
        }

        if (array_key_exists('implementingOffices', $valueArray) && !empty($valueArray['implementingOffices']['id'])) {
            $departments = explode("/", $valueArray['implementingOffices']['id']);
            $data = array();
            foreach ($departments as $id) {
                $implementingOffice = new ImplementingOffice();
                $implementingOffice->department = new Department();
                $implementingOffice->department->id = $id;
                array_push($data, $implementingOffice);
            }
            $this->implementingOffices = $data;
        }

        parent::bindValuesUsingArray($valueArray, $this);

        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function getAttributeNames() {
        return array(
            'title' => 'Initiative',
            'description' => 'Description',
            'beneficiaries' => 'Beneficiaries',
            'eoNumber' => 'EO Number',
            'advisers' => 'Advisers',
            'objectives' => 'Objectives',
            'leadMeasures' => 'Lead Meaures',
            'implementingOffices' => 'Implementing Offices',
            'initiativeEnvironmentStatus' => 'Status'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
