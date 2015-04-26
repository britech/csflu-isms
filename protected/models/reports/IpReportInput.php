<?php

namespace org\csflu\isms\models\reports;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\UserAccount;

/**
 * Description of IpReportInput
 *
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property UserAccount $user
 * @author britech
 */
class IpReportInput extends Model {

    private $startingPeriod;
    private $endingPeriod;
    private $user;

    public function validate() {
        if (!$this->startingPeriod instanceof \DateTime) {
            array_push($this->validationMessages, '- Starting Date is not defined');
        }

        if (!$this->endingPeriod instanceof \DateTime) {
            array_push($this->validationMessages, '- Ending Date is not defined');
        }

        if (!$this->user instanceof UserAccount) {
            array_push($this->validationMessages, '- User is not defined');
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('user', $valueArray)) {
            $this->user = new UserAccount();
            $this->user->id = $valueArray['user']['id'];
        }
        parent::bindValuesUsingArray($valueArray, $this);

        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function getAttributeNames() {
        return array(
            'startingPeriod' => 'Starting Date',
            'endingPeriod' => 'Ending Date',
            'user' => 'Owner'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
