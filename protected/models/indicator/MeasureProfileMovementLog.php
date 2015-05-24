<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\UserAccount;

/**
 * Description of MeasureProfileMovementLog
 *
 * @property UserAccount $user
 * @property string $notes
 * @property \DateTime $dateEntered
 * @author britech
 */
class MeasureProfileMovementLog extends Model {

    private $user;
    private $notes;
    private $dateEntered;

    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
