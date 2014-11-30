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
    private $environmentStatus = self::TYPE_ACTIVE;
    public $period;

    public function validate() {
        $counter = 0;

        if (empty($this->description)) {
            array_push($this->validationMessages, '- Description should be defined');
            $counter++;
        }

        if (empty($this->perspective->id)) {
            $id = $this->perspective->id;
            if (empty($id)) {
                array_push($this->validationMessages, '- Perspective should be defined');
                $counter++;
            }
        }

        if (empty($this->startingPeriodDate) || empty($this->endingPeriodDate)) {
            array_push($this->validationMessages, '- Periods should be defined');
            $counter++;
        } else {
            $this->startingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->startingPeriodDate, new \DateTimeZone('Asia/Manila'));
            $this->endingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->endingPeriodDate, new \DateTimeZone('Asia/Manila'));
        }

        return $counter == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('perspective', $valueArray)) {
            $this->perspective = new Perspective();
            $this->perspective->bindValuesUsingArray($valueArray, $this->perspective);
        }

        if (array_key_exists('theme', $valueArray)) {
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

    public function getAttributeNames() {
        return array(
            'description' => 'Objective',
            'perspective' => 'Perspective',
            'theme' => 'Theme',
            'period' => 'Periods Covered',
            'startingPeriodDate' => 'Period Start',
            'endingPeriodDate' => 'Period End',
            'environmentStatus' => 'Status'
        );
    }
    
    public function getModelTranslationAsNewEntity() {
        return "[Objective added]\n\n"
        . "Objective:\t{$this->description}\n"
        . "Perspective:\t{$this->perspective->description}\n"
        . "Status:\t{$this->getEnvironmentStatus()[$this->environmentStatus]}\n"
        . "Starting Period Date:\t{$this->startingPeriodDate->format('F-Y')}\n"
        . "Ending Period Date:\t{$this->endingPeriodDate->format('F-Y')}";
    }
    
    public function isNew() {
        return empty($this->id);
    }

}
