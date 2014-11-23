<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Objective;

/**
 * @property String $id
 * @property String $name
 * @property String $visionStatement
 * @property String $missionStatement
 * @property String $valuesStatement
 * @property String $strategyType
 * @property \DateTime $startingPeriodDate
 * @property \DateTime $endingPeriodDate
 * @property \DateTime $implementationDate
 * @property \DateTime $terminationDate
 * @property String $strategyEnvironmentStatus
 * @property Objective[] $objectives
 *
 * @author britech
 */
class StrategyMap extends Model {

    const TYPE_LONG = 'LT';
    const TYPE_MEDIUM = 'MT';
    const TYPE_SHORT = 'ST';
    const STATUS_DRAFT = 'D';
    const STATUS_ACTIVE = 'A';
    const STATUS_HALT = 'H';
    const STATUS_COMPLETED = 'C';
    const STATUS_TERMINATED = 'T';
    const LENGTH_MEDIUM_MIN = 35;
    const LENGTH_MEDIUM_MAX = 72;
    const LENGTH_SHORT_MIN = 11;
    const LENGTH_SHORT_MAX = 15;

    private $id;
    private $name;
    private $visionStatement;
    private $missionStatement;
    private $valuesStatement;
    private $strategyType;
    private $startingPeriodDate;
    private $endingPeriodDate;
    private $implementationDate;
    private $terminationDate;
    private $strategyEnvironmentStatus;
    private $objectives;

    public function validate() {
        $counter = 0;
        if ($this->validationMode == Model::VALIDATION_MODE_INITIAL) {
            if (empty($this->name)) {
                array_push($this->validationMessages, '- Map Description should be defined');
                $counter++;
            }

            if (empty($this->visionStatement)) {
                array_push($this->validationMessages, '- Vision Statement should be defined');
                $counter++;
            }

            /*             * if (empty($this->missionStatement)) {
              array_push($this->validationMessages, '- Mission Statement should be defined');
              $counter++;
              }

              if (empty($this->valuesStatement)) {
              array_push($this->validationMessages, '- Values Statement should be defined');
              $counter++;
              }* */

            if (empty($this->startingPeriodDate) || empty($this->endingPeriodDate)) {
                array_push($this->validationMessages, '- Periods should be defined');
                $counter++;
            } else {
                $this->startingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->startingPeriodDate, new \DateTimeZone('Asia/Manila'));
                $this->endingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->endingPeriodDate, new \DateTimeZone('Asia/Manila'));
                $counter+=$this->validateDateRange();
            }
        } elseif ($this->validationMode == Model::VALIDATION_MODE_UPDATE) {
            if (empty($this->visionStatement)) {
                array_push($this->validationMessages, '- Vision Statement should be defined');
                $counter++;
            }

            if (empty($this->missionStatement)) {
                array_push($this->validationMessages, '- Mission Statement should be defined');
                $counter++;
            }

            if (empty($this->valuesStatement)) {
                array_push($this->validationMessages, '- Values Statement should be defined');
                $counter++;
            }

            $this->startingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->startingPeriodDate, new \DateTimeZone('Asia/Manila'));
            $this->endingPeriodDate = \DateTime::createFromFormat('Y-m-d', $this->endingPeriodDate, new \DateTimeZone('Asia/Manila'));
            $counter+=$this->validateDateRange();
        }
        return $counter == 0;
    }

    private function validateDateRange() {
        $counter = 0;
        $interval = $this->endingPeriodDate->diff($this->startingPeriodDate, false);
        $monthLength = $interval->format('%y') > 0 ? $interval->format('%y') * 12 : $interval->format('%m');
        switch ($this->strategyType) {
            case self::TYPE_SHORT:
                if (!($monthLength >= self::LENGTH_SHORT_MIN && $monthLength <= self::LENGTH_MEDIUM_MAX)) {
                    $counter++;
                }
                break;
            case self::TYPE_MEDIUM:
                if (!($monthLength >= self::LENGTH_MEDIUM_MIN && $monthLength <= self::LENGTH_MEDIUM_MAX)) {
                    $counter++;
                }
                break;
            case self::TYPE_LONG:
                if ($monthLength < self::LENGTH_MEDIUM_MAX) {
                    $counter++;
                }
                break;
        }
        if ($counter > 0) {
            array_push($this->validationMessages, "- Selected Period Range does not match the Strategy Type selected ({$this->getStrategyTypes()[$this->strategyType]})");
        }
        return $counter;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('objective', $valueArray)) {
            //do nothing
        }
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public static function getStrategyTypes() {
        return array(
            self::TYPE_LONG => 'Long Term',
            self::TYPE_MEDIUM => 'Medium Term',
            self::TYPE_SHORT => 'Short Term'
        );
    }

    public static function getEnvironmentStatusTypes() {
        return array(
            self::STATUS_DRAFT => 'Draft Stage',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_HALT => 'Halted',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_TERMINATED => 'Terminated'
        );
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function getModelTranslationAsNewEntity() {
        return "[Strategy Map Added]\n\nStrategy Map:\t" . $this->name
                . "\nVision Statement:\t" . $this->visionStatement
                . "\nMission Statement:\t" . $this->missionStatement
                . "\nValues Statement:\t" . $this->valuesStatement
                . "\nStrategy Type:\t" . self::getStrategyTypes()[$this->strategyType]
                . "\nPeriod Dates:\t" . $this->startingPeriodDate->format('F-Y') . ' - ' . $this->endingPeriodDate->format('F-Y');
    }

    public function computePropertyChanges($oldModel) {
        $counter = 0;

        if ($oldModel->name != $this->name) {
            $counter++;
        }
        
        if ($oldModel->visionStatement != $this->visionStatement) {
            $counter++;
        }
        
        if ($oldModel->missionStatement != $this->missionStatement) {
            $counter++;
        }
        
        if ($oldModel->valuesStatement != $this->valuesStatement) {
            $counter++;
        }
        
        if ($oldModel->strategyType != $this->strategyType) {
            $counter++;
        }
        
        if ($oldModel->startingPeriodDate != $this->startingPeriodDate) {
            $counter++;
        }
        
        if ($oldModel->endingPeriodDate != $this->endingPeriodDate) {
            $counter++;
        }
        
        if ($oldModel->implementationDate != $this->implementationDate) {
            $counter++;
        }
        
        if ($oldModel->terminationDate != $this->terminationDate) {
            $counter++;
        }
        
        if ($oldModel->strategyEnvironmentStatus != $this->strategyEnvironmentStatus) {
            $counter++;
        }
        
        return $counter;
    }
    
    public function getModelTranslationAsUpdatedEntity($oldModel) {
        $translation = "[Strategy Map updated]\n\n";
        $changes = array();
        
        if ($oldModel->name != $this->name) {
            array_push($changes, "Name:\t{$this->name}");
        }
        
        if ($oldModel->visionStatement != $this->visionStatement) {
            array_push($changes, "Vision Statement:\t{$this->visionStatement}");
        }
        
        if ($oldModel->missionStatement != $this->missionStatement) {
            array_push($changes, "Mission Statement:\t{$this->missionStatement}");
        }
        
        if ($oldModel->valuesStatement != $this->valuesStatement) {
            array_push($changes, "Values Statement:\t{$this->valuesStatement}");
        }
        
        if ($oldModel->strategyType != $this->strategyType) {
            array_push($changes, "Strategy Type:\t{$this->strategyType}");
        }
        
        if ($oldModel->startingPeriodDate != $this->startingPeriodDate) {
            array($changes, "Starting Period Date:\t{$this->startingPeriodDate->format('F-Y')}");
        }
        
        if ($oldModel->endingPeriodDate != $this->endingPeriodDate) {
            array($changes, "Ending Period Date:\t{$this->endingPeriodDate->format('F-Y')}");
        }
        
        if ($oldModel->implementationDate != $this->implementationDate) {
            array($changes, "Date Implemented:\t{$this->implementationDate->format('M d, Y')}");
        }
        
        if ($oldModel->terminationDate != $this->terminationDate) {
            if($this->strategyEnvironmentStatus == StrategyMap::STATUS_COMPLETED){
                array($changes, "Date Completed:\t{$this->implementationDate->format('M d, Y')}");
            } else {
                array($changes, "Date Terminated:\t{$this->implementationDate->format('M d, Y')}");
            }
        }
        
        if ($oldModel->strategyEnvironmentStatus != $this->strategyEnvironmentStatus) {
            array_push($changes, "Status:\t{$this->strategyEnvironmentStatus}");
        }
        
        return $translation . implode("\n", $changes);
    }
    
    public function __clone() {
        $strategyMap = new StrategyMap;
        
        $strategyMap->id = $this->id;
        $strategyMap->name = $this->name;
        $strategyMap->visionStatement = $this->visionStatement;
        $strategyMap->missionStatement = $this->missionStatement;
        $strategyMap->valuesStatement = $this->valuesStatement;
        $strategyMap->strategyType = $this->strategyType;
        $strategyMap->startingPeriodDate = $this->startingPeriodDate;
        $strategyMap->endingPeriodDate = $this->endingPeriodDate;
        $strategyMap->implementationDate = $this->implementationDate;
        $strategyMap->terminationDate = $this->terminationDate;
        $strategyMap->strategyEnvironmentStatus = $this->strategyEnvironmentStatus;
    }
}
