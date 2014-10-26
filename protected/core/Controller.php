<?php

namespace org\csflu\isms\core;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;

/**
 * 
 * @property String $title
 * @property String $layout
 * @author britech
 *
 */
class Controller {

    protected $title;
    public $layout = "column-1";

    /**
     * 
     * @param mixed $view
     * @param array $params
     * @throws \Exception
     */
    public function render($view, $params = []) {
        $fileLocation = $this->generateFileName($view);

        if (file_exists($fileLocation)) {
            $body = $fileLocation;
        } else {
            throw new \Exception("Resource does not exist ({$view}.php)");
        }

        require_once "protected/views/layouts/{$this->layout}.php";
    }
    
    public function renderPartial($view, $params=[]){
        $fileLocation = $this->generateFileName($view);
        
        if(file_exists($fileLocation)){
            include_once $fileLocation;
        } else{
            $this->viewWarningPage('Included File Does Not Exist', 'The defined file to be rendered cannot be found.');
        }
    }
    
    public function renderAjaxJsonResponse(array $response){
        echo json_encode($response);
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
        $this->renderPartial('commons/warning', array('header'=>$header, 'message'=>$message));
    }

    public function checkAuthorization(){
        if(empty($_SESSION['employee']) || empty($_SESSION['user'])){
            $_SESSION['login.notif']="Please enter your user credentials to continue.";
            $this->redirect(array('site/login'));
        }
    }
}
