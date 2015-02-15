<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\ubt\LeadMeasure;

/**
 * Description of UnitBreakthrough
 * 
 * @property String $id
 * @property String $description
 * @property String $baselineFigure
 * @property String $targetFigure
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property Objective[] $objectives
 * @property MeasureProfile[] $indicators
 * @property LeadMeasure[] $leadMeasures
 * @property String $unitBreakthroughEnvironmentStatus
 * @author britech
 */
class UnitBreakthrough extends Model {

    private $id;
    private $description;
    private $baselineFigure;
    private $targetFigure;
    private $startingPeriod;
    private $endingPeriod;
    private $objectives;
    private $indicators;
    private $leadMeasures;
    private $unitBreakthroughEnvironmentStatus;

    public function validate() {
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Unit Breakthrough must be defined');
        }

        if (empty($this->startingPeriod) || empty($this->endingPeriod)) {
            array_push($this->validationMessages, '- Timeline should be defined');
        }
        
        if($this->validationMode == parent::VALIDATION_MODE_INITIAL){
            if(count($this->objectives) == 0){
                array_push($this->validationMessages, '- Objectives to be aligned should be defined');
            }
            
            if(count($this->indicators) == 0){
                array_push($this->validationMessages, '- Measure Profile to be aligned should be defined');
            }
        }
        
        return count($this->validationMessages) == 0;
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

        if (array_key_exists('indicators', $valueArray) && !empty($valueArray['indicators']['id'])) {
            $indicators = explode("/", $valueArray['indicators']['id']);
            $data = array();
            foreach ($indicators as $id) {
                $indicator = new MeasureProfile();
                $indicator->id = $id;
                array_push($data, $indicator);
            }
            $this->indicators = $data;
        }
        parent::bindValuesUsingArray($valueArray, $this);
        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
