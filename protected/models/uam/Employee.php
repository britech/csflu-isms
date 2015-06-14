<?php

namespace org\csflu\isms\models\uam;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\Position;
use org\csflu\isms\models\uam\LoginAccount;

/**
 * @property Integer $id
 * @property String $lastName
 * @property String $givenName
 * @property String $middleName
 * @property Department $department
 * @property Position $position
 * @property LoginAccount $loginAccount
 * @author britech
 *
 */
class Employee extends Model {

    private $id;
    private $lastName;
    private $givenName;
    private $middleName;
    private $department;
    private $position;
    private $loginAccount;

    public function validate() {
        
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('department', $valueArray)) {
            $this->department = new Department();
            $this->department->bindValuesUsingArray($valueArray, $this->department);
        }

        if (array_key_exists('position', $valueArray)) {
            $this->position = new Position();
            $this->position->bindValuesUsingArray($valueArray, $this->position);
        }

        if (array_key_exists('loginaccount', $valueArray)) {
            $this->loginAccount = new LoginAccount();
            $this->loginAccount->bindValuesUsingArray($valueArray, $this->loginAccount);
        }
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public function getAttributeNames() {
        return array(
            'lastName' => 'Last Name',
            'givenName' => 'First Name',
            'department' => 'Department',
            'position' => 'Position'
        );
    }

    public function getShortName() {
        $firstName = substr($this->givenName, 0, 1);
        return "{$firstName}. {$this->lastName}";
    }

    public function getFullName() {
        return "{$this->givenName} {$this->lastName}";
    }

    public function formulateUsername() {
        $firstName = trim(substr($this->givenName, 0, 1));
        $lastName = trim(implode("", explode(" ", $this->lastName)));
        return strtolower(trim("{$firstName}{$lastName}"));
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
