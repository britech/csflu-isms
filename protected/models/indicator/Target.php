<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * @property String $id
 * @property String $dataGroup
 * @property \DateTime $periodStart
 * @property \DateTime $periodEnd
 * @property String $value
 * @property String $notes
 */
class Target extends Model {

    private $id;
    private $dataGroup;
    private $periodStart;
    private $periodEnd;
    private $value;
    private $notes;
    
    public function validate() {
        
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
