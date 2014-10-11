<?php
namespace org\csflu\isms\core;

use org\csflu\isms\util\UrlResolver as UrlResolver;

/**
 * 
 * @property String $title
 * @author britech
 *
 */

class Controller {
	public function render($view, $params = []){
		$fileLocation = $this->generateFileName($view);
		
		if(file_exists($fileLocation)){
			$body = $fileLocation;
		} else{
			throw new \Exception('Resource does not exist');
		}
		require_once 'protected/views/commons/main.php';
	}
	
	public function redirect($url = []){
		header("location: ".UrlResolver::resolveUrl($url));
	}
	
	private function generateFileName($view){
		return 'protected/views/'.$view.'.php';
	}
	
	public function viewErrorPage($exception){
		$this->render('commons/error', array('exception'=>$exception));
	}
}