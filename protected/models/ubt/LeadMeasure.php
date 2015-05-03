<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\UnitOfMeasure;

/**
 * Description of LeadMeasure
 *
 * @property String $id
 * @property String $description
 * @property int $designation
 * @property String $targetFigure
 * @property UnitOfMeasure $uom
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property String $leadMeasureEnvironmentStatus
 * @author britech
 */
class LeadMeasure extends Model {

    const STATUS_ACTIVE = "A";
    const STATUS_INACTIVE = "I";
    const STATUS_COMPLETE = "C";
    const DESIGNATION_0 = 0;
    const DESIGNATION_1 = 1;
    const DESIGNATION_2 = 2;

    private $id;
    private $description;
    private $designation = self::DESIGNATION_1;
    private $targetFigure;
    private $uom;
    private $leadMeasureEnvironmentStatus = self::STATUS_ACTIVE;
    private $startingPeriod;
    private $endingPeriod;

    public static function listEnvironmentStatus() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_COMPLETE => 'Completed'
        );
    }

    public static function listDesignationTypes() {
        return array(
            self::DESIGNATION_0 => 'Disabled',
            self::DESIGNATION_1 => 'Lead Measure 1',
            self::DESIGNATION_2 => 'Lead Measure 2'
        );
    }

    public function translateEnvironmentStatus($environmentStatusCode = null) {
        $statusCode = is_null($environmentStatusCode) ? $this->leadMeasureEnvironmentStatus : $environmentStatusCode;
        if (array_key_exists($statusCode, self::listEnvironmentStatus())) {
            return self::listEnvironmentStatus()[$statusCode];
        }
        return null;
    }

    public function translateDesignationType($designationCode = null) {
        $designation = is_null($designationCode) ? $this->designation : $designationCode;
        if (array_key_exists($designation, self::listDesignationTypes())) {
            return self::listDesignationTypes()[$designation];
        }
        return null;
    }

    public function getAttributeNames() {
        return array(
            'description' => 'Lead Measure',
            'designation' => 'Designation',
            'baselineFigure' => 'Baseline',
            'targetFigure' => 'Target',
            'uom' => 'Unit of Measure',
            'leadMeasureEnvironmentStatus' => 'Status'
        );
    }

    public function validate() {
        if (strlen($this->description) == 0) {
            array_push($this->validationMessages, '-&nbsp;Lead Measure should be defined');
        }

        if (!$this->uom instanceof UnitOfMeasure) {
            array_push($this->validationMessages, '- Unit of Measure should be defined');
        }

        if (!array_key_exists($this->designation, self::listDesignationTypes())) {
            array_push($this->validationMessages, '- Designation invalid');
        }

        if (strlen($this->targetFigure) < 1) {
            array_push($this->validationMessages, '- Target Figure should be defined');
        }

        if (strlen($this->targetFigure) > 1 && !is_numeric($this->targetFigure)) {
            array_push($this->validationMessages, '- Target Figure should be in numerical representation');
        }

        if ($this->leadMeasureEnvironmentStatus == self::STATUS_ACTIVE && $this->designation == self::DESIGNATION_0) {
            array_push($this->validationMessages, '- Lead Measure should be not active if the designation is disabled');
        }

        if (!($this->startingPeriod instanceof \DateTime || $this->endingPeriod instanceof \DateTime)) {
            array_push($this->validationMessages, '- Timeline should be defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('uom', $valueArray) && !empty($valueArray['uom']['id'])) {
            $this->uom = new UnitOfMeasure();
            $this->uom->bindValuesUsingArray(array('unitofmeasure' => $valueArray['uom']), $this->uom);
        }
        parent::bindValuesUsingArray($valueArray, $this);

        $this->designation = intval($this->designation);
        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function getModelTranslationAsNewEntity() {
        return "[LeadMeasure added]\n\n"
                . "Description:\t{$this->description}\n"
                . "Designation:\t{$this->translateDesignationType()}\n"
                . "Timeline:\t{$this->startingPeriod->format('M. Y')} to {$this->endingPeriod->format('M. Y')}\n"
                . "Status:\t{$this->translateEnvironmentStatus()}\n"
                . "Target:\t{$this->targetFigure} {$this->uom->description}";
    }

    public function computePropertyChanges(LeadMeasure $oldModel) {
        $counter = 0;
        if ($oldModel->description != $this->description) {
            $counter++;
        }

        if ($oldModel->leadMeasureEnvironmentStatus != $this->leadMeasureEnvironmentStatus) {
            $counter++;
        }

        if ($oldModel->designation != $this->designation) {
            $counter++;
        }

        if ($oldModel->targetFigure != $this->targetFigure) {
            $counter++;
        }

        if ($oldModel->uom->id != $this->uom->id) {
            $counter++;
        }

        if ($oldModel->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($oldModel->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $counter++;
        }

        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(LeadMeasure $oldModel) {
        $translation = "[LeadMeasure updated]\n\n";
        if ($oldModel->description != $this->description) {
            $translation.="Description:\t{$this->description}\n";
        }

        if ($oldModel->leadMeasureEnvironmentStatus != $this->leadMeasureEnvironmentStatus) {
            $translation.="Status:\t{$this->translateEnvironmentStatus()}\n";
        }

        if ($oldModel->designation != $this->designation) {
            $translation.="Designation:\t{$this->translateDesignationType()}\n";
        }

        if ($oldModel->targetFigure != $this->targetFigure) {
            $translation.="Target:\t{$this->targetFigure} {$this->uom->description}\n";
        }

        if ($oldModel->uom->id != $this->uom->id) {
            $translation.="Unit of Measure:\t{$this->uom->description}\n";
        }

        if ($oldModel->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $translation.="Starting Period:\t{$this->startingPeriod->format('M. Y')}\n";
        }

        if ($oldModel->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $translation.="Ending Period:\t{$this->endingPeriod->format('M. Y')}\n";
        }

        return $translation;
    }

    public function isNew() {
        return empty($this->id);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
