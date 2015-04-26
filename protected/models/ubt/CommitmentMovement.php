<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;

/**
 * Description of CommitmentMovement
 *
 * @property String $movementFigure
 * @property String $notes
 * @property \DateTime $dateCaptured
 * @author britech
 */
class CommitmentMovement extends Model {

    private $movementFigure;
    private $notes;
    private $dateCaptured;

    public function validate() {
        if (strlen($this->movementFigure) == 0) {
            array_push($this->validationMessages, '- Movement Figure should be defined');
        }

        if (strlen($this->notes) == 0) {
            array_push($this->validationMessages, '- Notes should be defined');
        }
        
        return count($this->validationMessages) == 0;
    }

    public function getAttributeNames() {
        return array(
            'movementFigure' => 'Movement Figure',
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
