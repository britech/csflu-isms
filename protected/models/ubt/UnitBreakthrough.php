<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\commons\UnitOfMeasure;

/**
 * Description of UnitBreakthrough
 * 
 * @property String $id
 * @property String $description
 * @property Department $unit
 * @property String $baselineFigure
 * @property String $targetFigure
 * @property UnitOfMeasure $uom
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property Objective[] $objectives
 * @property MeasureProfile[] $measures
 * @property LeadMeasure[] $leadMeasures
 * @property WigSession[] $wigMeetings
 * @property String $unitBreakthroughEnvironmentStatus
 * @author britech
 */
class UnitBreakthrough extends Model {

    const STATUS_ACTIVE = "A";
    const STATUS_INACTIVE = "I";
    const STATUS_COMPLETED = "C";

    private $id;
    private $description;
    private $unit;
    private $baselineFigure;
    private $targetFigure;
    private $uom;
    private $startingPeriod;
    private $endingPeriod;
    private $objectives;
    private $measures;
    private $leadMeasures;
    private $wigMeetings;
    private $unitBreakthroughEnvironmentStatus = self::STATUS_ACTIVE;

    public static function listUbtStatusCodes() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_COMPLETED => 'Completed'
        );
    }

    public static function translateUbtStatusCode($statusCode) {
        if (array_key_exists($statusCode, self::listUbtStatusCodes())) {
            return self::listUbtStatusCodes()[$statusCode];
        }
        return null;
    }

    public function getAttributeNames() {
        return array(
            'description' => 'Unit Breakthrough',
            'unit' => 'Department',
            'objectives' => 'Objectives',
            'measures' => 'Indicators',
            'leadMeasures' => 'Lead Measures',
            'baselineFigure' => 'Baseline Figure',
            'targetFigure' => 'Target Figure',
            'uom' => 'Unit Of Measure'
        );
    }

    public function validate() {
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Unit Breakthrough must be defined');
        }

        if (!$this->startingPeriod instanceof \DateTime || !$this->endingPeriod instanceof \DateTime) {
            array_push($this->validationMessages, '- Timeline should be defined');
        }

        if (!$this->unit instanceof Department) {
            array_push($this->validationMessages, '- Implementing Office should be defined');
        }

        if (!$this->uom instanceof UnitOfMeasure) {
            array_push($this->validationMessages, '- Unit of Measure should be defined');
        }

        if (strlen($this->baselineFigure) < 1) {
            array_push($this->validationMessages, '- Baseline Figure should be defined');
        }

        if (strlen($this->targetFigure) < 1) {
            array_push($this->validationMessages, '- Target Figure should be defined');
        }

        if (strlen($this->baselineFigure) > 1 && !is_numeric($this->baselineFigure)) {
            array_push($this->validationMessages, '- Baseline Figure should be in numerical representation');
        }

        if (strlen($this->targetFigure) > 1 && !is_numeric($this->targetFigure)) {
            array_push($this->validationMessages, '- Target Figure should be in numerical representation');
        }

        if ($this->validationMode == parent::VALIDATION_MODE_INITIAL) {
            if (count($this->objectives) == 0) {
                array_push($this->validationMessages, '- Objectives to be aligned should be defined');
            }

            if (count($this->measures) == 0) {
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

        if (array_key_exists('measures', $valueArray) && !empty($valueArray['measures']['id'])) {
            $measures = explode("/", $valueArray['measures']['id']);
            $data = array();
            foreach ($measures as $id) {
                $measureProfile = new MeasureProfile();
                $measureProfile->id = $id;
                array_push($data, $measureProfile);
            }
            $this->measures = $data;
        }

        if (array_key_exists('unit', $valueArray) && !empty($valueArray['unit']['id'])) {
            $this->unit = new Department();
            $this->unit->bindValuesUsingArray(array('department' => $valueArray['unit']), $this->unit);
        }

        if (array_key_exists('uom', $valueArray) && !empty($valueArray['uom']['id'])) {
            $this->uom = new UnitOfMeasure();
            $this->uom->bindValuesUsingArray(array('unitofmeasure' => $valueArray['uom']), $this->uom);
        }

        parent::bindValuesUsingArray($valueArray, $this);
        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getModelTranslationAsNewEntity() {
        return "[UnitBreakthrough added]\n\n"
                . "Department:\t{$this->unit->name}\n"
                . "Unit Breakthrough:\t{$this->description}\n"
                . "Timeline:\t{$this->startingPeriod->format('F-Y')} - {$this->endingPeriod->format('F-Y')}\n"
                . "Baseline: {$this->baselineFigure} {$this->uom->description}\n"
                . "Target: {$this->targetFigure} {$this->uom->description}";
    }

    public function computePropertyChanges(UnitBreakthrough $oldModel) {
        $counter = 0;

        if ($oldModel->description != $this->description) {
            $counter++;
        }

        if ($oldModel->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($oldModel->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($oldModel->unit->id != $this->unit->id) {
            $counter++;
        }

        if ($oldModel->baselineFigure != $this->baselineFigure) {
            $counter++;
        }

        if ($oldModel->targetFigure != $this->targetFigure) {
            $counter++;
        }

        if ($oldModel->uom->id != $this->uom->id) {
            $counter++;
        }

        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(UnitBreakthrough $oldModel) {
        $translation = "[UnitBreakthrough updated]\n\n";
        if ($oldModel->description != $this->description) {
            $translation.="Unit Breakthrough:\t{$this->description}\n";
        }

        if ($oldModel->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $translation.="Starting Period:\t{$this->startingPeriod->format('F-Y')}\n";
        }

        if ($oldModel->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $translation.="Ending Period:\t{$this->endingPeriod->format('F-Y')}\n";
        }

        if ($oldModel->unit->id != $this->unit->id) {
            $translation.="Unit:\t{$this->unit->name}\n";
        }

        if ($oldModel->baselineFigure != $this->baselineFigure) {
            $translation.="Baseline:\t{$this->baselineFigure} {$this->uom->description}\n";
        }

        if ($oldModel->targetFigure != $this->targetFigure) {
            $translation.="Target:\t{$this->targetFigure} {$this->uom->description}\n";
        }

        if ($oldModel->uom->id != $this->uom->id) {
            $translation.="Unit of Measure:\t{$this->uom->description}\n";
        }

        return $translation;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->id = $this->id;
        $unitBreakthrough->startingPeriod = $this->startingPeriod;
        $unitBreakthrough->endingPeriod = $this->endingPeriod;

        $unitBreakthrough->unit = new Department();
        $unitBreakthrough->unit->id = $this->unit->id;
        $unitBreakthrough->unit->name = $this->unit->name;
        $unitBreakthrough->unit->code = $this->unit->code;

        $unitBreakthrough->objectives = array();
        foreach ($this->objectives as $objective) {
            array_push($unitBreakthrough->objectives, $objective);
        }

        $unitBreakthrough->measures = array();
        foreach ($this->measures as $measure) {
            array_push($unitBreakthrough->measures, $measure);
        }

        $unitBreakthrough->leadMeasures = array();
        foreach ($this->leadMeasures as $leadMeasure) {
            array_push($unitBreakthrough->leadMeasures, $leadMeasure);
        }
    }

}
