<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
use org\csflu\isms\models\indicator\MeasureProfileMovementLog;

/**
 * Description of ScorecardControllerSupport
 *
 * @author britech
 */
class ScorecardControllerSupport {

    private static $instance = null;
    private $controller;
    private $modelLoaderUtil;
    private $logger;

    private function __construct(Controller $controller) {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->controller = $controller;
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($controller);
    }

    /**
     * Returns the singleton instance of the support controller class
     * @param Controller $controller
     * @return ScorecardControllerSupport
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new ScorecardControllerSupport($controller);
        }
        return self::$instance;
    }

    /**
     * Constructs the MeasureProfileMovement entity
     * @return MeasureProfileMovement
     */
    public function constructMovementEntity() {
        $measureProfileMovementData = $this->controller->getFormData('MeasureProfileMovement');
        $measureProfileMovement = new MeasureProfileMovement();
        $measureProfileMovement->bindValuesUsingArray(array(
            'measureprofilemovement' => $measureProfileMovementData
        ));
        
        return $measureProfileMovement;
    }

    /**
     * Constructs the MeasureProfileMovementLog entity
     * @return MeasureProfileMovementLog
     */
    public function constructMovementLogEntity() {
        $measureProfileMovementLogData = $this->controller->getFormData('MeasureProfileMovementLog');
        $measureProfileMovementLog = new MeasureProfileMovementLog();
        $measureProfileMovementLog->bindValuesUsingArray(array(
            'measureprofilemovementlog' => $measureProfileMovementLogData), $measureProfileMovementLog);
        $measureProfileMovementLog->user = $this->modelLoaderUtil->loadAccountModel();

        return $measureProfileMovementLog;
    }

}
