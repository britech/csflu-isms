<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * Description of MeasureProfileMovement
 *
 * @property string $id
 * @property \DateTime $periodDate
 * @property string $movementValue
 * @property MeasureProfileMovementLog[] $movementLogs
 * @author britech
 */
class MeasureProfileMovement extends Model {

    private $id;
    private $periodDate;
    private $movementValue;
    private $movementLogs;

    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
