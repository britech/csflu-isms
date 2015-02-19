<?php

namespace org\csflu\isms\core;

use org\csflu\isms\core\Controller as Controller;
use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;

class Application {

    private $controller = 'site';
    private $action = 'index';
    private $logger;
    private static $instance;

    private function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new Application();
        }
        return self::$instance;
    }

    public function runApplication() {
        $this->logger->info("[" . filter_input(INPUT_SERVER, 'REQUEST_METHOD') . "] " . "[Client: " . filter_input(INPUT_SERVER, 'REMOTE_ADDR') . "]" . " Route Expression: " . filter_input(INPUT_GET, 'r'));
        $request = filter_input(INPUT_GET, 'r');
        try {
            $this->resolveAndDispatchRequest($request);
        } catch (\Exception $e) {
            $this->logger->error("[" . filter_input(INPUT_SERVER, 'REQUEST_METHOD') . "] " . "[Client: " . filter_input(INPUT_SERVER, 'REMOTE_ADDR') . "]", $e);
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
            if (!$reflectionParameter->allowsNull() && !array_key_exists($reflectionParameter->name, filter_input_array(INPUT_GET))) {
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
                if ($data != 'r') {
                    array_push($inputData, $value);
                }
            }
        }
        return $inputData;
    }

}
