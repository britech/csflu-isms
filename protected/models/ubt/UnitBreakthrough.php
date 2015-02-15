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
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
