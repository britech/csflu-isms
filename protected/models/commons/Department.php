<?php

namespace org\csflu\isms\models\commons;

use org\csflu\isms\core\Model as Model;
use org\csflu\isms\models\uam\Employee as Employee;

/**
 * @property Integer $id
 * @property String $code
 * @property String $name
 * @property Employee $headEmployee
 * @property String $parentDepartmentName
 * 
 * @author britech
 *
 */
class Department extends Model {

    private $id;
    private $code;
    private $name;
    private $headEmployee;
    private $parentDepartmentName;


    public function validate() {
        
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
