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

}
