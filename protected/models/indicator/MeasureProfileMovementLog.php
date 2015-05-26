<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\UserAccount;

/**
 * Description of MeasureProfileMovementLog
 *
 * @property UserAccount $user
 * @property string $notes
 * @property \DateTime $dateEntered
 * @author britech
 */
class MeasureProfileMovementLog extends Model {

    private $user;
    private $notes;
    private $dateEntered;

    public function validate() {
        if (!$this->user instanceof UserAccount) {
            array_push($this->validationMessages, '- User should be defined');
        }

        if (!strlen($this->notes) > 0) {
            array_push($this->validationMessages, '- Notes should be defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('user', $valueArray)) {
            $this->user = new UserAccount();
            $this->user->id = $valueArray['user']['id'];
        }
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public function getAttributeNames() {
        return array(
            'user' => 'User',
            'notes' => 'Remarks'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
