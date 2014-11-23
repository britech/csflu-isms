<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;

/**
 * Description of Perspective
 *
 * @property String $id
 * @property String $description
 * @property Integer $positionOrder
 * @author britech
 */
class Perspective extends Model {

    private $id;
    private $description;
    private $positionOrder;

    public function validate() {
        $counter = 0;
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Description should be defined');
            $counter++;
        }

        if ($this->validationMode == Model::VALIDATION_MODE_INITIAL) {
            if (empty($this->positionOrder)) {
                array_push($this->validationMessages, '- Position Order should be defined');
                $counter++;
            } elseif (is_numeric($this->positionOrder)) {
                $counter+=$this->validateRange($counter);
            }
        }

        return $counter === 0;
    }

    private function validateRange($counter) {
        if (!($this->positionOrder > 0 && $this->positionOrder < 6)) {
            array_push($this->validationMessages, '- Position Order out of range.');
            return $counter;
        }
        return 0;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function getModelTranslationAsNewEntity() {
        return "[Perspective added]\n\nDescription:\t{$this->description}\n"
                . "Position Order:\t{$this->positionOrder}";
    }

    public function getModelTranslationAsUpdatedEntity($oldModel) {
        $translation = "[Perspective updated]\n\n";

        $changes = array();
        if ($oldModel->description != $this->description) {
            array_push($changes, "Description:\t{$this->description}");
        }

        return $translation . implode("\n", $changes);
    }

    public function getModelTranslationAsDeletedEntity() {
        return "[Perspective deleted]\n\nDescription:\t{$this->description}";
    }

    public function computePropertyChanges($oldModel) {
        return $oldModel->description != $this->description ? 1 : 0;
    }

    public function __clone() {
        $perspective = new Perspective();
        $perspective->id = $this->id;
        $perspective->description = $this->description;
        $perspective->positionOrder = $this->positionOrder;
    }

}
