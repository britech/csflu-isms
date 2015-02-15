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
 * @author britech
 */
class LeadMeasure extends Model {

    private $id;
    private $description;
    private $baselineFigure;
    private $targetFigure;
    
    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
