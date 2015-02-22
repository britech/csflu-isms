<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\UnitBreakthroughMovement;
/**
 * Description of WigMeeting
 *
 * @property String $id
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property Commitment[] $commitments
 * @property UnitBreakthroughMovement $movementUpdate Description
 * @property String $wigMeetingEnvironmentStatus
 * @author britech
 */
class WigMeeting extends Model {

    const STATUS_OPEN = "O";
    const STATUS_CLOSED = "C";

    private $id;
    private $startingPeriod;
    private $endingPeriod;
    private $commitments;
    private $movementUpdate;
    private $wigMeetingEnvironmentStatus = self::STATUS_OPEN;

    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
