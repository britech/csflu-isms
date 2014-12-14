<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * Description of Baseline
 *
 * @property String $id
 * @property String $baselineDataGroup
 * @property String $coveredYear
 * @property String $value
 * @property String $notes
 * @author britech
 */
class Baseline extends Model {

    private $id;
    private $baselineDataGroup;
    private $coveredYear;
    private $value;
    private $notes;

    public function validate() {
        $counter = 0;
        if (empty($this->coveredYear)) {
            $counter+=1;
            array_push($this->validationMessages, '- Covered Year must be defined');
        }

        if (empty($this->value) && $this->value != 0) {
            $counter+=1;
            array_push($this->validationMessages, '- Figure Value should be defined');
        }

        return $counter > 0 ? false : true;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
