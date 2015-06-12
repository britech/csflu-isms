<?php

namespace org\csflu\isms\models\initiative;

use org\csflu\isms\core\Model;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\commons\Department;

/**
 * Description of Initiative
 *
 * @property String $id
 * @property String $title
 * @property String $description
 * @property String $beneficiaries
 * @property \DateTime $startingPeriod
 * @property \DateTime $endingPeriod
 * @property String $eoNumber
 * @property String $advisers
 * @property Objective[] $objectives
 * @property MeasureProfile[] $leadMeasures
 * @property ImplementingOffice[] $implementingOffices
 * @property Phase[] $phases
 * @property String $initiativeEnvironmentStatus
 * @author britech
 */
class Initiative extends Model {

    const STATUS_ACTIVE = "A";
    const STATUS_INACTIVE = "I";
    const STATUS_COMPLETED = "C";
    const STATUS_TERMINATED = "T";

    private $id;
    private $title;
    private $description;
    private $beneficiaries;
    private $startingPeriod;
    private $endingPeriod;
    private $eoNumber;
    private $advisers;
    private $objectives;
    private $leadMeasures;
    private $implementingOffices;
    private $phases;
    private $initiativeEnvironmentStatus = self::STATUS_ACTIVE;

    public static function getEnvironmentStatusTypes() {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_TERMINATED => 'Terminated'
        );
    }

    public function translateStatusCode($code = null) {
        $statusCode = is_null($code) ? $this->initiativeEnvironmentStatus : $code;
        if (array_key_exists($statusCode, self::getEnvironmentStatusTypes())) {
            return self::getEnvironmentStatusTypes()[$statusCode];
        }
        return "Undefined";
    }

    public function validate() {
        if (empty($this->title)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['title']} should be defined");
        }

        if (empty($this->description)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['description']} should be defined");
        }

        if (empty($this->beneficiaries)) {
            array_push($this->validationMessages, "- {$this->getAttributeNames()['beneficiaries']} should be defined");
        }

        if ($this->validationMode == self::VALIDATION_MODE_INITIAL) {
            if (count($this->objectives) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['objectives']} should be defined");
            }

            if (count($this->leadMeasures) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['leadMeasures']} should be defined");
            }

            if (count($this->implementingOffices) == 0) {
                array_push($this->validationMessages, "- {$this->getAttributeNames()['implementingOffices']} should be defined");
            }
        }
        return count($this->validationMessages) == 0;
    }

    public function bindValuesUsingArray(array $valueArray) {
        if (array_key_exists('objectives', $valueArray) && !empty($valueArray['objectives']['id'])) {
            $objectives = explode("/", $valueArray['objectives']['id']);
            $data = array();
            foreach ($objectives as $id) {
                $objective = new Objective();
                $objective->id = $id;
                array_push($data, $objective);
            }
            $this->objectives = $data;
        }

        if (array_key_exists('leadMeasures', $valueArray) && !empty($valueArray['leadMeasures']['id'])) {
            $leadMeasures = explode("/", $valueArray['leadMeasures']['id']);
            $data = array();
            foreach ($leadMeasures as $id) {
                $leadMeasure = new MeasureProfile();
                $leadMeasure->id = $id;
                array_push($data, $leadMeasure);
            }
            $this->leadMeasures = $data;
        }

        if (array_key_exists('implementingOffices', $valueArray) && !empty($valueArray['implementingOffices']['id'])) {
            $departments = explode("/", $valueArray['implementingOffices']['id']);
            $data = array();
            foreach ($departments as $id) {
                $implementingOffice = new ImplementingOffice();
                $implementingOffice->department = new Department();
                $implementingOffice->department->id = $id;
                array_push($data, $implementingOffice);
            }
            $this->implementingOffices = $data;
        }

        parent::bindValuesUsingArray($valueArray, $this);

        $this->startingPeriod = \DateTime::createFromFormat('Y-m-d', $this->startingPeriod);
        $this->endingPeriod = \DateTime::createFromFormat('Y-m-d', $this->endingPeriod);
    }

    public function getAttributeNames() {
        return array(
            'title' => 'Initiative',
            'description' => 'Description',
            'beneficiaries' => 'Beneficiaries',
            'eoNumber' => 'EO Number',
            'advisers' => 'Advisers',
            'objectives' => 'Objectives',
            'leadMeasures' => 'Lead Meaures',
            'implementingOffices' => 'Implementing Offices',
            'initiativeEnvironmentStatus' => 'Status'
        );
    }

    public function getModelTranslationAsNewEntity() {
        return "[Initiative added]\n\n"
                . "Title:\t{$this->title}\n"
                . "Description:\t{$this->description}\n"
                . "Beneficiaries:\t{$this->beneficiaries}\n"
                . "Starting Period:\t{$this->startingPeriod->format('F-Y')}\n"
                . "Ending Period:\t{$this->endingPeriod->format('F-Y')}\n"
                . "Status:\t{$this->getEnvironmentStatusTypes()[$this->initiativeEnvironmentStatus]}\n"
                . "EO Number:\t{$this->eoNumber}\n"
                . "Advisers:\t{$this->advisers}";
    }

    public function computePropertyChanges(Initiative $oldInitiative) {
        $counter = 0;

        if ($oldInitiative->title != $this->title) {
            $counter++;
        }

        if ($oldInitiative->description != $this->description) {
            $counter++;
        }

        if ($oldInitiative->beneficiaries != $this->beneficiaries) {
            $counter++;
        }

        if ($oldInitiative->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($oldInitiative->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $counter++;
        }

        if ($oldInitiative->initiativeEnvironmentStatus != $this->initiativeEnvironmentStatus) {
            $counter++;
        }

        if ($oldInitiative->eoNumber != $this->eoNumber) {
            $counter++;
        }

        if ($oldInitiative->advisers != $this->advisers) {
            $counter++;
        }

        return $counter;
    }

    public function getModelTranslationAsUpdatedEntity(Initiative $oldInitiative) {
        $translation = "[Initiative updated]\n\n";

        if ($oldInitiative->title != $this->title) {
            $translation.="Title:\t{$this->title}\n";
        }

        if ($oldInitiative->description != $this->description) {
            $translation.="Description:\t{$this->description}\n";
        }

        if ($oldInitiative->beneficiaries != $this->beneficiaries) {
            $translation.="Beneficiaries:\t{$this->beneficiaries}\n";
        }

        if ($oldInitiative->startingPeriod->format('Y-m-d') != $this->startingPeriod->format('Y-m-d')) {
            $translation.="Starting Period:\t{$this->startingPeriod->format('F-Y')}\n";
        }

        if ($oldInitiative->endingPeriod->format('Y-m-d') != $this->endingPeriod->format('Y-m-d')) {
            $translation.="Ending Period:\t{$this->endingPeriod->format('F-Y')}\n";
        }

        if ($oldInitiative->initiativeEnvironmentStatus != $this->initiativeEnvironmentStatus) {
            $translation.="Status:\t{$this->getEnvironmentStatusTypes()[$this->initiativeEnvironmentStatus]}\n";
        }

        if ($oldInitiative->eoNumber != $this->eoNumber) {
            $translation.="EO Number:\t{$this->eoNumber}\n";
        }

        if ($oldInitiative->advisers != $this->advisers) {
            $translation.="Advisers:\t{$this->advisers}\n";
        }

        return $translation;
    }

    public function isNew() {
        return empty($this->id);
    }

    public function filterPhases(\DateTime $period) {
        $filteredPhases = array();
        foreach ($this->phases as $phase) {
            $filteredComponents = array();
            foreach ($phase->components as $component) {
                $activities = $this->filterActivities($component, $period);
                if (count($activities) > 0) {
                    $component->activities = $activities;
                    $filteredComponents = array_merge($filteredComponents, array($component));
                }
            }
            if (count($filteredComponents) > 0) {
                $phase->components = $filteredComponents;
                $filteredPhases = array_merge($filteredPhases, array($phase));
            }
        }
        return $filteredPhases;
    }

    private function filterActivities(Component $component, \DateTime $date) {
        $activities = array();
        foreach ($component->activities as $activity) {
            if ($activity->startingPeriod == $date || ($date <= $activity->endingPeriod && $date >= $activity->startingPeriod)) {
                $activities = array_merge($activities, array($activity));
            }
        }
        return $activities;
    }

    private function computeAccomplishmentRate(\DateTime $date) {
        $phases = $this->filterPhases($date);
        if (count($phases) > 0) {
            $numberOfActivities = 0;
            $completionPercentage = 0.00;
            foreach ($phases as $phase) {
                $numberOfActivities += $this->countTotalActivities($phase);
                $completionPercentage += $this->countTotalCompletionPercentage($phase, $date);
            }
            return $completionPercentage / $numberOfActivities;
        }
        return 0;
    }

    private function countTotalActivities(Phase $phase) {
        $numberOfActivities = 0;
        foreach ($phase->components as $component) {
            $numberOfActivities += count($component->activities);
        }
        return $numberOfActivities;
    }

    public function countActivities() {
        $totalActivities = 0;
        foreach($this->phases as $phase){
            $totalActivities += $this->countTotalActivities($phase);
        }
        return $totalActivities;
    }

    private function countTotalCompletionPercentage(Phase $phase, \DateTime $period) {
        $completionPercentage = 0.00;
        foreach ($phase->components as $component) {
            $completionPercentage += $component->computeTotalCompletionPercentage($period);
        }
        return $completionPercentage;
    }

    public function resolvePeriodicalAccomplishmentRate(\DateTime $date) {
        return number_format($this->computeAccomplishmentRate($date), 2) . " %";
    }

    private function computeBudgetBurnRate(\DateTime $date) {
        $phases = $this->filterPhases($date);
        if (count($phases) > 0) {
            $budgetAmount = 0.00;
            $utilizedBudgetAmount = 0.00;
            foreach ($phases as $phase) {
                $budgetAmount += $this->countTotalBudgetAllocation($phase);
                $utilizedBudgetAmount += $this->countTotalRemainingBudget($phase, $date);
            }
            return (($budgetAmount - $utilizedBudgetAmount) / $budgetAmount) * 100;
        }
        return 0;
    }

    private function countTotalBudgetAllocation(Phase $phase) {
        $budgetAmount = 0.00;
        foreach ($phase->components as $component) {
            $budgetAmount += $component->computeTotalBudgetAllocation();
        }
        return $budgetAmount;
    }

    private function countTotalRemainingBudget(Phase $phase, \DateTime $period) {
        $utilizedBudget = 0.00;
        foreach ($phase->components as $component) {
            $utilizedBudget += $component->computeTotalRemainingBudget($period);
        }
        return $utilizedBudget;
    }

    public function resolvePeriodicalBudgetBurnRate(\DateTime $date) {
        return number_format($this->computeBudgetBurnRate($date), 2) . " %";
    }

    public function resolveTotalBudgetAllocation() {
        $budgetAmount = 0.00;
        foreach ($this->phases as $phase) {
            foreach ($phase->components as $component) {
                $budgetAmount+=$component->computeTotalBudgetAllocation();
            }
        }
        return 'PHP ' . number_format($budgetAmount);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
