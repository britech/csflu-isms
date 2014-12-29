<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * @property String $id
 * @property String $dataGroup
 * @property String $coveredYear
 * @property String $value
 * @property String $notes
 */
class Target extends Model {

    private $id;
    private $dataGroup;
    private $coveredYear;
    private $value;
    private $notes;

    public function validate() {
        
    }

    public function getAttributeNames() {
        return array(
            'dataGroup' => 'Item',
            'coveredYear' => 'Year Covered',
            'value' => 'Figure Value',
            'notes' => 'Notes'
        );
    }

    public function isNew() {
        return empty($this->id);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
