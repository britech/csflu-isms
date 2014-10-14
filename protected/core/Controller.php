<?php

namespace org\csflu\isms\core;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;

/**
 * 
 * @property String $title
 * @author britech
 *
 */
class Controller {

    public $layout = "main";
    
    public function render($view, $params = []) {
        $fileLocation = $this->generateFileName($view);

        if (file_exists($fileLocation)) {
            $body = $fileLocation;
        } else {
            throw new \Exception('Resource does not exist');
        }
        require_once "protected/views/layouts/{$this->layout}.php";
    }

    public function redirect(array $url) {
        header("location: " . ApplicationUtils::resolveUrl($url));
    }

    private function generateFileName($view) {
        return 'protected/views/' . $view . '.php';
    }

    public function viewErrorPage($exception) {
        $this->layout = "simple";
        $this->render('commons/error', array('exception' => $exception));
    }

    public function viewWarningPage($header, $message) {
        $this->layout = "simple";
        $this->render('commons/warning', array('header'=>$header, 'message' => $message));
    }

}
