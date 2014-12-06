<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ValidationException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\commons\RevisionHistoryLoggingServiceImpl as RevisionHistoryLoggingService;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\core\Model;

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

        extract($params);

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

    protected function redirect($url) {
        if (is_array($url)) {
            header("location: " . ApplicationUtils::resolveUrl($url));
        } else {
            header("location: {$url}");
        }
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
        if (empty($this->getSessionData('employee')) || empty($this->getSessionData('user'))) {
            $_SESSION['login.notif'] = "Please enter your user credentials to continue.";
            $this->redirect(array('site/login'));
        }
    }

    protected function logRevision($revisionType, $module, $referenceId, Model $model, Model $oldModel = null) {
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
            case RevisionHistory::TYPE_DELETE:
                $this->loggingService->logDeleteAction($revision, $model);
                break;
        }
    }

    protected function remoteValidateModel(Model $model) {
        if (!$model->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $model->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    protected function validatePostData(array $keyNames) {
        $counter = 0;
        $data = filter_input_array(INPUT_POST);
        if (is_null($data) || empty($data)) {
            throw new ValidationException("Parameter/s are needed to process this request");
        }

        foreach ($keyNames as $key) {
            if (!array_key_exists($key, $data)) {
                $counter++;
            }
        }

        if ($counter > 0) {
            throw new ValidationException("Parameter/s are needed to process this request");
        }
    }
    
    protected function getFormData($indexName){
        return filter_input_array(INPUT_POST)[$indexName];
    }

    protected function getSessionData($key) {
        return (isset($_SESSION[$key]) && !empty($_SESSION[$key])) ? $_SESSION[$key] : "";
    }

    protected function setSessionData($key, $value) {
        $_SESSION[$key] = $value;
    }

    protected function unsetSessionData($key) {
        unset($_SESSION[$key]);
    }
}
