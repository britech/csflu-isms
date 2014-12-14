<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\models\indicator\Baseline;

/**
 * @property String $id
 * @property String $description
 * @property String $rationale
 * @property String $formula
 * @property String $dataSource
 * @property String $dataSourceStatus
 * @property String $dataSourceAvailabilityDate
 * @property UnitOfMeasure $uom
 * @property Baseline[] $baselineData
 */
class Indicator extends Model {

    private $id;
    private $description;
    private $rationale;
    private $formula;
    private $dataSource;
    private $dataSourceStatus;
    private $dataSourceAvailabilityDate;
    private $uom;
    private $baselineData = array();

    const STAT_AVAILABLE = "CA";
    const STAT_MINOR_CHANGE = "WMC";
    const STAT_FORMULATED = "SF";

    public static function getDataSourceDescriptionList() {
        return array(
            self::STAT_AVAILABLE => 'Currently Available',
            self::STAT_MINOR_CHANGE => 'With Minor Changes',
            self::STAT_FORMULATED => 'Still to be Formulated'
        );
    }

    public function validate() {
        $counter = 0;
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Description should not be empty');
            $counter += 1;
        }

        if (empty($this->uom->id)) {
            $id = $this->uom->id;
            if (empty($id)) {
                array_push($this->validationMessages, '- Unit of Measure should be selected');
                $counter += 1;
            }
        }

        if ($this->validationMode == parent::VALIDATION_MODE_UPDATE) {
            if (empty($this->rationale)) {
                array_push($this->validationMessages, '- Rationale should not be empty');
                $counter += 1;
            }

            if (empty($this->formula)) {
                array_push($this->validationMessages, '- Formula Description should not be empty');
                $counter += 1;
            }

            if (empty($this->dataSource)) {
                array_push($this->validationMessages, '- Source of Data should not be empty');
                $counter += 1;
            }

            if (empty($this->dataSourceAvailabilityDate) && ($this->dataSourceStatus != self::STAT_AVAILABLE)) {
                array_push($this->validationMessages, '- Date of Availability - Source of Data should not be empty');
                $counter += 1;
            }
        }

        return $counter > 0 ? false : true;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('unitofmeasure', $valueArray)) {
            $this->uom = new UnitOfMeasure();
            $this->uom->bindValuesUsingArray($valueArray, $this->uom);
        }

        if (array_key_exists('baseline', $valueArray)) {
            $this->baselineData = new Baseline();
            $this->baselineData->bindValuesUsingArray($valueArray, $this->baselineData);
        }
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getAttributeNames() {
        return array(
            'description' => 'Description',
            'rationale' => 'Rationale',
            'formula' => 'Formula Description',
            'dataSource' => 'Source of Data',
            'dataSourceStatus' => 'Status - Source of Data',
            'dataSourceAvailabilityDate' => 'Date of Availability - Source of Data',
            'uom' => 'Unit of Measure'
        );
    }

}
