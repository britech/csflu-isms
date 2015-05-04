<?php

namespace org\csflu\isms\models\ubt;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\uam\UserAccount;
use org\csflu\isms\models\ubt\CommitmentMovement;

/**
 * Description of Commitment
 *
 * @property String $id
 * @property UserAccount $user
 * @property String $commitment
 * @property String $commitmentTargetFigure
 * @property CommitmentMovement[] $commitmentMovements
 * @property String $commitmentEnvironmentStatus
 * @author britech
 */
class Commitment extends Model {

    const STATUS_PENDING = "P";
    const STATUS_ONGOING = "A";
    const STATUS_FINISHED = "F";
    const STATUS_UNFINISHED = "U";

    private $id;
    private $user;
    private $commitment;
    private $commitmentTargetFigure;
    private $commitmentMovements;
    private $commitmentEnvironmentStatus = self::STATUS_PENDING;
    
    public function translateStatusCode($code = null){
        $status = is_null($code)? $this->commitmentEnvironmentStatus : strtoupper($code);
        $description = "";
        
        switch ($status){
            case self::STATUS_PENDING:
                $description = "Pending";
                break;
            case self::STATUS_ONGOING:
                $description = "On-going";
                break;
            case self::STATUS_FINISHED:
                $description = "Finished";
                break;
            case self::STATUS_UNFINISHED:
                $description = "Incomplete";
                break;
            default:
                $description = "Undefined";
        }
        
        return $description;
    }

    public function validate() {
        if(strlen($this->commitment) == 0){
            array_push($this->validationMessages, '* Commitment should be defined');
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
            'user' => 'Employee',
            'commitment' => 'Commitment',
            'commitmentTargetFigure' => 'Numerical Target',
            'commitmentEnvironmentStatus' => 'Status'
        );
    }

    public function computePropertyChanges(Commitment $oldModel) {
        $counter = 0;
        
        if(strcasecmp($this->commitment, $oldModel->commitment) != 0){
            $counter+=1;
            array_push($this->updatedFields, 'commitment');
        }
        
        if($this->commitmentEnvironmentStatus != $oldModel->commitmentEnvironmentStatus){
            $counter+=1;
            array_push($this->updatedFields, 'commitmentEnvironmentStatus');
        }
        
        return $counter;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
