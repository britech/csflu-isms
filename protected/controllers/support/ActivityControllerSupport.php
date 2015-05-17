<?php

namespace org\csflu\isms\controllers\support;

use org\csflu\isms\core\Controller;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\models\initiative\ActivityMovement;
use org\csflu\isms\models\initiative\Activity;

/**
 * Description of ActivityControllerSupport
 *
 * @author britech
 */
class ActivityControllerSupport {

    private static $instance = null;
    private $controller;
    private $modelLoaderUtil;

    private function __construct(Controller $controller) {
        $this->controller = $controller;
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($controller);
    }

    /**
     * Gets the singleton instance of the support class
     * @param Controller $controller
     * @return ActivityControllerSupport
     */
    public static function getInstance(Controller $controller) {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityControllerSupport($controller);
        }
        return self::$instance;
    }

    /**
     * Constructs the ActivityMovement entity
     * @param string $status Optional. Indicates that the activity has just changed the status code
     * @return ActivityMovement
     */
    public function constructActivityMovementEntity($status = null) {
        $movement = new ActivityMovement();
        $movement->user = $this->modelLoaderUtil->loadAccountModel();

        if (is_null($status)) {
            $activityMovementData = $this->controller->getFormData('ActivityMovement');
            $movement->bindValuesUsingArray(array('activitymovement' => $activityMovementData), $movement);
        } else {
            $movement->notes = "Activity set to " . Activity::listEnvironmentStatusCodes()[$status];
        }

        return $movement;
    }

}
