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

    public function validate() {
        if (empty($this->username) || empty($this->password)) {
            return false;
        } else {
            return true;
        }
    }

    public function bindValuesUsingArray($valuesArray = []) {
        $this->username = $valuesArray['username'];
        $this->password = $valuesArray['password'];
    }

    public function getAttributeNames() {
        return array('username' => 'Username', 'password' => 'Password');
    }

}
