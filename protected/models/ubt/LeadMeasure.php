<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of LeadMeasure
 *
 * @property String $id
 * @property String $description
 * @property String $baselineFigure
 * @property String $targetFigure
 * @property String $leadMeasureEnvironmentStatus
 * @author britech
 */
class LeadMeasure extends Model {

    const STATUS_ACTIVE = "A";
    const STATUS_INACTIVE = "I";
    const STATUS_COMPLETE = "C";

    private $id;
    private $description;
    private $baselineFigure;
    private $targetFigure;
    private $leadMeasureEnvironmentStatus = self::STATUS_ACTIVE;

    public static function listEnvironmentStatus() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_COMPLETE => 'Completed'
        );
    }

    public static function translateEnvironmentStatus($environmentStatusCode) {
        if (array_key_exists($environmentStatusCode, self::listEnvironmentStatus())) {
            return self::listEnvironmentStatus()[$environmentStatusCode];
        }
        return null;
    }

    public function validate() {
        if(strlen($this->description) == 0){
            array_push($this->validationMessages, '-&nbsp;Lead Measure should be defined');
        }
        return count($this->validationMessages) == 0;
    }

    public function getModelTranslationAsNewEntity() {
        return "[LeadMeasure added]\n\n"
                . "Description:\t{$this->description}";
    }

    public function computePropertyChanges(LeadMeasure $oldModel) {
        $counter = 0;
        if ($oldModel->description != $this->description) {
            $counter++;
        }

        if ($oldModel->leadMeasureEnvironmentStatus != $this->leadMeasureEnvironmentStatus) {
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
            $translation.="Status:\t{$this->translateEnvironmentStatus($this->leadMeasureEnvironmentStatus)}";
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
