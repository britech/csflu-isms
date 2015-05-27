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

    public function getAttributeNames() {
        return array(
            'user' => 'User',
            'notes' => 'Remarks'
        );
    }

    public function getModelTranslationAsNewEntity() {
        return "[Log Entry]\n\n"
                . "User:\t{$this->user->employee->givenName} {$this->user->employee->lastName}\n"
                . "Remarks:\t" . implode("\n", explode("+", $this->notes));
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
