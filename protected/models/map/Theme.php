<?php

namespace org\csflu\isms\models\map;

use org\csflu\isms\core\Model;

/**
 * 
 * @property String $id
 * @property String $description
 *
 * @author britech
 */
class Theme extends Model {

    private $id;
    private $description;

    public function validate() {
        $counter = 0;
        if (empty($this->description)) {
            array_push($this->validationMessages, '- Theme should be defined');
            $counter++;
        }
        return $counter == 0;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function getModelTranslationAsNewEntity() {
        return "[Theme added]\n\nDescription:\t{$this->description}";
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getAttributeNames() {
        return array(
            'description' => 'Description'
        );
    }

    public function computePropertyChanges(Theme $oldModel) {
        return $oldModel->description != $this->description ? 1 : 0;
    }
    
    public function getModelTranslationAsUpdatedEntity(Theme $oldModel) {
        $translation = "[Theme updated]\n\n";
        
        $changes = array();
        
        if($oldModel->description != $this->description){
            array_push($changes, "Description:\t{$this->description}");
        }
        return $translation.implode("\n", $changes);
    }
    
    public function getModelTranslationAsDeletedEntity() {
        return "[Theme deleted]\n\nDescription:\t{$this->description}";
    }

    public function __clone() {
        $theme = new Theme();
        $theme->id = $this->id;
        $theme->description = $this->description;
    }

}
