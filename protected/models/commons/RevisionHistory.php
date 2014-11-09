<?php

namespace org\csflu\isms\models\commons;

use org\csflu\isms\models\uam\Employee;

/**
 * @property String $module
 * @property String $referenceId
 * @property Employee $employee
 * @property String $notes
 * @property String $revisionType
 * @property \DateTime $revisionTimestamp
 * @author britech
 */
class RevisionHistory {

    const TYPE_INSERT = "A";
    const TYPE_UPDATE = "U";

    private $module;
    private $referenceId;
    private $employee;
    private $notes;
    private $revisionType;
    private $revisionTimestamp;

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public static function getRevisionTypes() {
        return array(self::TYPE_INSERT => 'New Entry', self::TYPE_UPDATE => 'Updated Entry');
    }

}
