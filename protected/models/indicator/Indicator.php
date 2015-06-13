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
    private $rationale = "";
    private $formula = "";
    private $dataSource = "";
    private $dataSourceStatus = "";
    private $dataSourceAvailabilityDate = "";
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

    public function computePropertyChanges(Indicator $oldModel) {
        $counter = 0;

        if ($this->description != $oldModel->description) {
            $counter++;
        }

        if ($this->uom->id != $oldModel->uom->id) {
            $counter++;
        }

        if (!empty($this->rationale) && $this->rationale != $oldModel->rationale) {
            $counter++;
        }

        if (!empty($this->formula) && $this->formula != $oldModel->formula) {
            $counter++;
        }

        if (!empty($this->dataSource) && $this->dataSource != $oldModel->dataSource) {
            $counter++;
        }

        if (!empty($this->dataSourceStatus) && $this->dataSourceStatus != $oldModel->dataSourceStatus) {
            $counter++;
        }

        if (!empty($this->dataSourceAvailabilityDate) && $this->dataSourceAvailabilityDate != $oldModel->dataSourceAvailabilityDate) {
            $counter++;
        }

        return $counter;
    }

    public function resolveBaselineValue($year) {
        $baselineValues = array();
        foreach ($this->baselineData as $baseline) {
            if ($year == $baseline->coveredYear && strlen($baseline->baselineDataGroup) > 0) {
                $baselineValues = array_merge($baselineValues, array("{$baseline->baselineDataGroup}: {$baseline->value}"));
            } elseif($year == $baseline->coveredYear && strlen($baseline->baselineDataGroup) < 1){
                $baselineValues = array_merge($baselineValues, array("$baseline->value"));
            }
        }
        return nl2br(implode("\n", $baselineValues));
    }
    
    public function getBaselineYears(){
        $baselineYears = array();
        foreach($this->baselineData as $baseline){
            if(!in_array($baseline->coveredYear, $baselineYears)){
                $baselineYears = array_merge($baselineYears, array($baseline->coveredYear));
            }
        }
        return $baselineYears;
    }
    
    public function resolveDataSourceDescription(){
        return nl2br(implode("\n", explode("+", $this->dataSource)));
    }

    public function __clone() {
        $indicator = new Indicator();
        $indicator->id = $this->id;
        $indicator->description = $this->description;
        $indicator->rationale = $this->rationale;
        $indicator->formula = $this->formula;
        $indicator->dataSource = $this->dataSource;
        $indicator->dataSourceStatus = $this->dataSourceStatus;
        $indicator->dataSourceAvailabilityDate = $this->dataSourceAvailabilityDate;
        $indicator->uom = $this->uom;
    }

}
