<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model as Model;

/**
 * @property String $username
 * @property String $password
 * @author britech
 *
 */
class Login extends Model {

    private $username;
    private $password;
    
    public function validate() {
        if (empty($this->username) || empty($this->password)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}