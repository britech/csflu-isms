<?php

namespace org\csflu\isms\models\indicator;

use org\csflu\isms\core\Model;

/**
 * @property String $id
 * @property String $dataGroup
 * @property String $coveredYear
 * @property String $value
 * @property String $notes
 */
class Target extends Model {

    private $id;
    private $dataGroup;
    private $coveredYear;
    private $value;
    private $notes;

    public function validate() {
        $counter = 0;
        if (empty($this->coveredYear)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['coveredYear']} should be defined");
            $counter++;
        }

        if (strlen($this->value) == 0) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['value']} should be defined");
            $counter++;
        }
        return $counter == 0;
    }

    public function getAttributeNames() {
        return array(
            'dataGroup' => 'Item',
            'coveredYear' => 'Year Covered',
            'value' => 'Figure Value',
            'notes' => 'Notes'
        );
    }
    
    public function getModelTranslationAsNewEntity() {
        return "[Target added]\n\n"
        . "Covered Year:\t{$this->coveredYear}\n"
        . "Value:\t{$this->value}\n"
        . "Group:\t{$this->dataGroup}\n"
        . "Notes:\t{$this->notes}";
    }

    public function isNew() {
        return empty($this->id);
    }
    
    public function computePropertyChanges(Target $oldTarget) {
        $counter = 0;

        if($oldTarget->coveredYear != $this->coveredYear){
            $counter++;
        }
        
        if($oldTarget->dataGroup != $this->dataGroup){
            $counter++;
        }
        
        if($oldTarget->value != $this->value){
            $counter++;
        }
        
        if($oldTarget->notes != $this->notes){
            $counter++;
        }
        
        return $counter;
    }
    
    public function getModelTranslationAsUpdatedEntity(Target $oldTarget) {
        $translation = "[Target updated]\n\n";
        
        if($oldTarget->coveredYear != $this->coveredYear){
            $translation.="Covered Year:\t{$this->coveredYear}\n";
        }
        
        if($oldTarget->dataGroup != $this->dataGroup){
            $translation.="Group:\t{$this->dataGroup}\n";
        }
        
        if($oldTarget->notes != $this->notes){
            $translation.="Notes:\t{$this->notes}\n";
        }
        
        if($oldTarget->value != $this->value){
            $translation.="Value:\t{$this->value}";
        }
        
        return $translation;
    }
    
    public function getModelTranslationAsDeletedEntity() {
        return "[Target deleted]\n\n"
        . "Covered Year:\t{$this->coveredYear}\n"
        . "Value:\t{$this->value}\n";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $target = new Target();
        $target->id = $this->id;
        $target->dataGroup = $this->dataGroup;
        $target->coveredYear = $this->coveredYear;
        $target->value = $this->value;
        $target->notes = $this->notes;
    }

    public function __toString() {
        return "[Target] (id=>{$this->id}, group=>{$this->dataGroup}, year=>{$this->coveredYear}, value=>{$this->value}, notes=>{$this->notes})";
    }

}
