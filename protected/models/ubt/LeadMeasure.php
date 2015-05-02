<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\UnitOfMeasure;

/**
 * Description of LeadMeasure
 *
 * @property String $id
 * @property String $description
 * @property int $designation Description
 * @property String $baselineFigure
 * @property String $targetFigure
 * @property UnitOfMeasure $uom
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
    private $baselineFigure;
    private $targetFigure;
    private $uom;
    private $leadMeasureEnvironmentStatus = self::STATUS_ACTIVE;

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

    public static function translateEnvironmentStatus($environmentStatusCode) {
        if (array_key_exists($environmentStatusCode, self::listEnvironmentStatus())) {
            return self::listEnvironmentStatus()[$environmentStatusCode];
        }
        return null;
    }

    public static function translateDesignationType($designationCode) {
        if (array_key_exists($designationCode, self::listDesignationTypes())) {
            return self::listDesignationTypes()[$designationCode];
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

        if (!array_key_exists(self::listDesignationTypes(), $this->designation)) {
            array_push($this->validationMessages, '- Designation invalid');
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

        if ($this->leadMeasureEnvironmentStatus == self::STATUS_ACTIVE && $this->designation == self::DESIGNATION_0) {
            array_push($this->validationMessages, 'Lead Measure should be not active if the designation is disabled');
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
    }

    public function getModelTranslationAsNewEntity() {
        return "[LeadMeasure added]\n\n"
                . "Description:\t{$this->description}\n"
                . "Designation:\t{$this->designation}\n"
                . "Baseline\t{$this->baselineFigure} {$this->uom->description}\n"
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

    public function getModelTranslationAsUpdatedEntity(LeadMeasure $oldModel) {
        $translation = "[LeadMeasure updated]\n\n";
        if ($oldModel->description != $this->description) {
            $translation.="Description:\t{$this->description}\n";
        }

        if ($oldModel->leadMeasureEnvironmentStatus != $this->leadMeasureEnvironmentStatus) {
            $status = self::translateEnvironmentStatus();
            $translation.="Status:\t{$status}\n";
        }

        if ($oldModel->designation != $this->designation) {
            $designation = self::translateDesignationType();
            $translation.="Designation:\t{$designation}\n";
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
