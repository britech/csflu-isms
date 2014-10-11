<?php
namespace org\csflu\isms\core;

use org\csflu\isms\core\Controller as Controller;
use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;

class Application {
	
	private $controller = 'site';
	private $action = 'index';
	
	public function runApplication(){
		$request = filter_input(INPUT_GET, 'r');
		try{
			$this->resolveAndDispatchRequest($request);
		} catch(\Exception $e){
			$controller = new Controller();
			$controller->title = ApplicationConstants::APP_NAME;
			$controller->viewErrorPage($e);
		}
	}
	
	private function resolveAndDispatchRequest($request){
		if(!empty($request)){
			$requestContent = explode('/', $request);
			if(!empty($requestContent[1])){
				$this->action = $requestContent[1];
			}
			$this->controller = $requestContent[0];	
		}
		$controller = $this->generateControllerClass($this->controller);
		
		if(method_exists($controller, $this->action)){
			call_user_func([$controller, $this->action]);
		} else{
			throw new \Exception('Action does not exist');
		}
	}
	
	private function generateControllerClass($controllerPrefix){
		$controller = ucwords($controllerPrefix).'Controller';
		
		$controllerLocation = 'protected/controllers/'.$controller.'.php';
		$controllerClassName = 'org\csflu\isms\controllers\\'.$controller;
		
		if(file_exists($controllerLocation)){
			require_once $controllerLocation;
			return new $controllerClassName;
		} else{
			throw new \Exception('Controller does not exist');	
		}
	}
}
?>