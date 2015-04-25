<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of CommitmentMovement
 *
 * @property String $progressFigure
 * @property String $notes
 * @property \DateTime $dateCaptured
 * @author britech
 */
class CommitmentMovement extends Model {

    private $progressFigure;
    private $notes;
    private $dateCaptured;

    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $name;
    }
}
