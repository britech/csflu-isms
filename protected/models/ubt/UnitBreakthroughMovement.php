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
        
    }

    public function getAttributeNames() {
        return array(
            'ubtFigure' => 'UBT Figure',
            'firstLeadMeasureFigure' => 'Lead Measure 1 Figure',
            'secondLeadMeasureFigure' => 'Lead Measure 2 Figure',
            'notes' => 'Notes'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
