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
class LoginAccount extends Model {

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    private $username;
    private $password;
    private $status;

    public function validate() {
        if (strlen($this->username) < 1) {
            array_push($this->validationMessages, '- Username should be defined');
        }

        if (strlen($this->password) < 1) {
            array_push($this->validationMessages, '- Password should be defined');
        }
        return count($this->validationMessages) == 0;
    }

    public static function listStatusTypes() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DISABLED => 'Inactive'
        );
    }

    public static function translateStatusType($statusType = null) {
        $type = is_null($statusType) ? $this->status : $statusType;
        if (array_key_exists($type, self::listStatusTypes())) {
            return self::listStatusTypes()[$type];
        }
        return "Undefined";
    }

    public function getAttributeNames() {
        return array(
            'username' => 'Username',
            'password' => 'Password'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
