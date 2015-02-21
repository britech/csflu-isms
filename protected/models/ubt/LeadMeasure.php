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
    const STATUS_STOPPED = "S";
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
            self::STATUS_STOPPED => 'Stopped/Dropped',
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
        
    }

    public function getModelTranslationAsNewEntity() {
        return "[LeadMeasure added]\n\n"
                . "Description:\t{$this->description}";
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
