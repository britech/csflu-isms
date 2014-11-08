<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\Theme;

/**
 *
 * @property String $id
 * @property String $description
 * @property String $strategicShiftStatement
 * @property String $agendaStatement
 * @property Perspective $perspective
 * @property Theme $theme
 * @property \DateTime $startingPeriodDate
 * @property \DateTime $endingPeriodDate
 * @property String $environmentStatus
 *
 * @author britech
 */
class Objective extends Model {

    const TYPE_ACTIVE = 'A';
    const TYPE_DROPPED = 'D';

    private $id;
    private $description;
    private $strategicShiftStatement;
    private $agendaStatement;
    private $perspective;
    private $theme;
    private $startingPeriodDate;
    private $endingPeriodDate;
    private $environmentStatus;

    public function validate() {
        
    }

    public function bindValuesUsingArray(array $valueArray) {
        if(array_key_exists('perspective', $valueArray)){
            $this->perspective = new Perspective();
            $this->perspective->bindValuesUsingArray($valueArray, $this->perspective);
        }
        
        if(array_key_exists('theme', $valueArray)){
            $this->theme = new Theme();
            $this->theme->bindValuesUsingArray($valueArray, $this->theme);
        }
        
        parent::bindValuesUsingArray($valueArray, $this);
    }
    public static function getEnvironmentStatus() {
        return array(
            self::TYPE_ACTIVE => 'Active',
            self::TYPE_DROPPED => 'Dropped'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
