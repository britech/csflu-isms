<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\models\ubt\UnitBreakthrough;

/**
 * Description of UnitBreakthroughControllerSupport
 *
 * @author britech
 */
class UnitBreakthroughControllerSupport {

    private static $instance = null;
    private $controller;
    private $logger;

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    /**
     * Gets the singleton instance of the UnitBreakthroughControllerSupport
     * @param Controller $controller
     * @return UnitBreakthroughControllerSupport
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new UnitBreakthroughControllerSupport($controller);
        }
        return self::$instance;
    }

    /**
     * Constructs the input data needed for the enlistment of the UnitBreakthrough
     * @return UnitBreakthrough
     */
    public function constructEnlistmentInputData() {
        $unitBreakthroughData = $this->controller->getFormData('UnitBreakthrough');
        $objectiveData = $this->controller->getFormData('Objective');
        $measureProfileData = $this->controller->getFormData('MeasureProfile');
        $departmentData = $this->controller->getFormData('Department');
        $uomData = $this->controller->getFormData('UnitOfMeasure');


        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'objectives' => $objectiveData,
            'measures' => $measureProfileData,
            'unit' => $departmentData,
            'uom' => $uomData
        ));

        return $unitBreakthrough;
    }

    /**
     * Constructs the input data needed for the update of the UnitBreakthrough
     * @return UnitBreakthrough
     */
    public function constructUpdateInputData() {
        $unitBreakthroughData = $this->controller->getFormData('UnitBreakthrough');
        $departmentData = $this->controller->getFormData('Department');
        $uomData = $this->controller->getFormData('UnitOfMeasure');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'unitbreakthrough' => $unitBreakthroughData,
            'unit' => $departmentData,
            'uom' => $uomData
        ));
        
        return $unitBreakthrough;
    }

}
