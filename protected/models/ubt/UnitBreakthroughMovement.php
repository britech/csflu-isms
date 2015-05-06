<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of UnitBreakthroughMovement
 *
 * @property String $id
 * @property \DateTime $dateEntered
 * @property String $ubtFigure
 * @property String $firstLeadMeasureFigure
 * @property String $secondLeadMeasureFigure
 * @property String $notes
 * @author britech
 */
class UnitBreakthroughMovement extends Model {

    private $id;
    private $dateEntered;
    private $ubtFigure;
    private $firstLeadMeasureFigure;
    private $secondLeadMeasureFigure;
    private $notes;

    public function validate() {
        if (strlen($this->notes) < 1) {
            array_push($this->validationMessages, '- Notes should be defined');
        }

        if (strlen($this->ubtFigure) > 1 && !is_numeric($this->ubtFigure)) {
            array_push($this->validationMessages, '- UBT Figure should in numerical representation');
        }

        if (strlen($this->firstLeadMeasureFigure) > 1 && !is_numeric($this->firstLeadMeasureFigure)) {
            array_push($this->validationMessages, '- Lead Measure 1 Figure should in numerical representation');
        }

        if (strlen($this->secondLeadMeasureFigure) > 1 && !is_numeric($this->secondLeadMeasureFigure)) {
            array_push($this->validationMessages, '- Lead Measure 2 Figure should in numerical representation');
        }

        return count($this->validationMessages) == 0;
    }

    public function getAttributeNames() {
        return array(
            'ubtFigure' => 'UBT Figure',
            'firstLeadMeasureFigure' => 'Lead Measure 1 Figure',
            'secondLeadMeasureFigure' => 'Lead Measure 2 Figure',
            'notes' => 'Notes'
        );
    }

    public function getModelTranslationAsNewEntity() {
        return "[Movement recorded]\nUBT:\t{$this->ubtFigure}\n"
                . "Lead Measure 1:\t{$this->firstLeadMeasureFigure}\n"
                . "Lead Measure 2:\t{$this->secondLeadMeasureFigure}\n"
                . "Notes:\t{$this->notes}";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
