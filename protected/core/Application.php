<?php

namespace org\csflu\isms\core;

session_start();
ob_start();

require_once dirname(__FILE__) . '/ApplicationLoader.php';

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\ApplicationEnvironment;
use org\csflu\isms\exceptions\ApplicationException;

\Logger::configure("protected/logs/logback.xml");

class Application {

    const ROUTE_IDENTIFIER = "r";

    private $controller = 'site';
    private $action = 'index';
    private $logger;
    private $configurationLocation;
    private static $instance;

    private function __construct($configurationLocation) {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->configurationLocation = $configurationLocation;
    }

    public static function getInstance($configurationLocation) {
        if (empty(self::$instance)) {
            self::$instance = new Application($configurationLocation);
        }
        return self::$instance;
    }

    public function runApplication() {
        try {
            ApplicationEnvironment::initialize($this->configurationLocation);
            $request = $this->retrieveRouteExpression();
            $this->resolveAndDispatchRequest($request);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $controller = new Controller();
            $controller->title = ApplicationConstants::APP_NAME;
            $controller->viewErrorPage($e);
        }
    }

    private function resolveAndDispatchRequest($request) {
        if (!empty($request)) {
            $requestContent = explode('/', $request);
            if (!empty($requestContent[1])) {
                $this->action = $requestContent[1];
            }
            $this->controller = $requestContent[0];
        }
        $controller = $this->generateControllerClass($this->controller);

        if (method_exists($controller, $this->action)) {
            $this->checkActionParameterIntegrity($controller);
            call_user_func_array([$controller, $this->action], $this->filterInputArguments(filter_input_array(INPUT_GET)));
        } else {
            throw new \Exception('Action does not exist');
        }
    }

    private function checkActionParameterIntegrity($class) {
        $reflectionMethod = new \ReflectionMethod($class, $this->action);
        $reflectionParameters = $reflectionMethod->getParameters();

        foreach ($reflectionParameters as $reflectionParameter) {
            if (!$reflectionParameter->isOptional() && !array_key_exists($reflectionParameter->name, filter_input_array(INPUT_GET))) {
                throw new \Exception("Defined argument is not recognized as a parameter of the selected action");
            }
        }
    }

    private function generateControllerClass($controllerPrefix) {
        $controller = ucwords($controllerPrefix) . 'Controller';

        $controllerLocation = 'protected/controllers/' . $controller . '.php';
        $controllerClassName = 'org\csflu\isms\controllers\\' . $controller;

        if (file_exists($controllerLocation)) {
            require_once $controllerLocation;
            return new $controllerClassName;
        } else {
            throw new \Exception('Controller does not exist');
        }
    }

    private function filterInputArguments($inputs) {
        $inputData = array();

        if (is_array($inputs)) {
            foreach ($inputs as $data => $value) {
                if ($data != self::ROUTE_IDENTIFIER) {
                    array_push($inputData, $value);
                }
            }
        }
        return $inputData;
    }

    private function retrieveRouteExpression() {
        $getParameters = filter_input_array(INPUT_GET);
        if (count($getParameters) != 0) {
            if (array_key_exists(self::ROUTE_IDENTIFIER, $getParameters)) {
                return filter_input(INPUT_GET, self::ROUTE_IDENTIFIER);
            } else {
                throw new ApplicationException("Internal Failure");
            }
        }
    }

}
