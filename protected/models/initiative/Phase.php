<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\initiative\Component;

/**
 * Description of Phase
 *
 * @property String $id
 * @property int $phaseNumber
 * @property String $title
 * @property String $description
 * @property Component[] $components
 * @author britech
 */
class Phase extends Model {

    private $id;
    private $phaseNumber;
    private $title;
    private $description;
    private $components;

    public function __construct($id = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
    }

    public function validate() {
        if (empty($this->phaseNumber)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['phaseNumber']} should be defined");
        }

        if (empty($this->title)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['title']} should be defined");
        }

        if (empty($this->description)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['description']} should be defined");
        }

        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('components', $valueArray) && !empty($valueArray['components']['description'])) {
            $components = explode("+", $valueArray['components']['description']);
            foreach ($components as $component) {
                $data = new Component();
                $data->description = $component;
                array_push($this->components, $data);
            }
        }
        parent::bindValuesUsingArray($valueArray, $this);
    }

    public function getModelTranslationAsNewEntity() {
        return "[Phase added]\n\nNumber:\t{$this->phaseNumber}\n"
                . "Title:\t{$this->title}\n"
                . "Description:\t{$this->description}";
    }

    public function computePropertyChanges(Phase $oldModel) {
        $counter = 0;
        if ($oldModel->description != $this->description) {
            $counter++;
        }

        if ($oldModel->title != $this->title) {
            $counter++;
        }
        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(Phase $oldModel) {
        $translation = "[Phase updated]\n\n";
        if ($oldModel->title != $this->title) {
            $translation.="Phase:\t{$this->title}";
        }

        if ($oldModel->description != $this->description) {
            $translation.="Description:\t{$this->description}";
        }
        return $translation;
    }

    public function getModelTranslationAsDeletedEntity() {
        return "[Phase deleted]\n\nTitle:\t{$this->title}";
    }

    public function getAttributeNames() {
        return array(
            'phaseNumber' => 'Phase Number',
            'title' => 'Phase',
            'description' => 'Description',
            'components' => 'Components'
        );
    }

    public function isNew() {
        return empty($this->id);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __clone() {
        $phase = new Phase();
        $phase->id = $this->id;
        $phase->title = $this->title;
        $phase->description = $this->description;
        $phase->phaseNumber = $this->phaseNumber;
    }

}
