<?php

namespace org\csflu\isms\core;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
use org\csflu\isms\service\commons\RevisionHistoryLoggingServiceImpl as RevisionHistoryLoggingService;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\Employee;

/**
 * 
 * @property String $title
 * @property String $layout
 * @author britech
 *
 */
class Controller {

    public $title;
    public $layout = "column-1";
    private $loggingService;

    /*
     * @param mixed $view
     * @param array $params
     * @throws \Exception
     */

    protected function render($view, $params = []) {
        $fileLocation = $this->generateFileName($view);

        if (file_exists($fileLocation)) {
            $body = $fileLocation;
        } else {
            throw new \Exception("Resource does not exist ({$view}.php)");
        }

        require_once "protected/views/layouts/{$this->layout}.php";
    }

    public function renderPartial($view, $params = []) {
        $fileLocation = $this->generateFileName($view);

        if (file_exists($fileLocation)) {
            include_once $fileLocation;
        } else {
            $this->viewWarningPage('Included File Does Not Exist', 'The defined file to be rendered cannot be found.');
        }
    }

    protected function renderAjaxJsonResponse(array $response) {
        echo json_encode($response);
    }

    protected function redirect(array $url) {
        header("location: " . ApplicationUtils::resolveUrl($url));
        die();
    }

    private function generateFileName($view) {
        return 'protected/views/' . $view . '.php';
    }

    public function viewErrorPage($exception) {
        $this->layout = "simple";
        $this->render('commons/error', array('exception' => $exception));
    }

    public function viewWarningPage($header, $message) {
        $this->renderPartial('commons/warning', array('header' => $header, 'message' => $message));
    }

    protected function checkAuthorization() {
        if (empty($_SESSION['employee']) || empty($_SESSION['user'])) {
            $_SESSION['login.notif'] = "Please enter your user credentials to continue.";
            $this->redirect(array('site/login'));
        }
    }

    protected function logRevision($revisionType, $module, $referenceId, $model, $oldModel = null) {
        $this->loggingService = new RevisionHistoryLoggingService();
        $revision = new RevisionHistory();
        $revision->employee = new Employee();
        $revision->employee->id = $_SESSION['employee'];
        $revision->module = $module;
        $revision->referenceId = $referenceId;
        $revision->revisionType = $revisionType;

        switch ($revision->revisionType) {
            case RevisionHistory::TYPE_INSERT:
                $this->loggingService->logNewAction($revision, $model);
                break;
            case RevisionHistory::TYPE_UPDATE:
                $this->loggingService->logUpdateAction($revision, $model, $oldModel);
                break;
        }
    }
    
    protected function remoteValidateModel($model){
        if(!$model->validate()){
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $model->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode'=>'00'));
        }
    }

}
