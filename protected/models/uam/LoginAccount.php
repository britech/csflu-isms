<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model;
/**
 * Description of LoginAccount
 *
 * @property String $username
 * @property String $password
 * @property Integer $status
 * @author britech
 */
class LoginAccount extends Model{
    
    private $username;
    private $password;
    private $status;
    
    public function validate() {
        
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}
