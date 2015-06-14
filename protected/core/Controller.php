<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\service\commons\RevisionHistoryLoggingServiceImpl as RevisionHistoryLoggingService;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\Employee;
use org\csflu\isms\core\Model;
use org\csflu\isms\service\uam\RbacServiceImpl;
use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl;

/**
 * 
 * @property String $title
 * @property String $layout
 * @author britech
 *
 */
class Controller {

    const COMPONENT_BREADCRUMB = "breadcrumb";
    const COMPONENT_SIDEBAR = "sidebar";
    const SUB_COMPONENT_SIDEBAR_FILE = "file";
    const SUB_COMPONENT_SIDEBAR_DATA = "data";
    const SUB_COMPONENT_SIDEBAR_DATA_HEADER = "header";
    const SUB_COMPONENT_SIDEBAR_DATA_LINKS = "links";

    public $title;
    public $layout = "column-1";
    private $loggingService;
    protected $isRbacEnabled = false;
    protected $moduleCode = "";
    protected $actionCode = "";

    /*
     * @param mixed $view
     * @param array $params
     * @throws \Exception
     */

    protected final function render($view, $params = []) {
        $fileLocation = $this->generateFileName($view);

        extract($params);

        if (file_exists($fileLocation)) {
            if ($this->isRbacEnabled) {
                $body = $this->initiateRbac($fileLocation);
            } else {
                $body = $fileLocation;
            }
        } else {
            throw new \Exception("Resource does not exist ({$view}.php)");
        }

        require_once "protected/views/layouts/{$this->layout}.php";
    }

    private function initiateRbac($fileName) {
        $rbacService = new RbacServiceImpl();

        $userService = new SimpleUserManagementServiceImpl();
        $userAccount = $userService->getAccountById($this->getSessionData('user'));
        if ($rbacService->validateRole($userAccount->securityRole, $this->moduleCode, $this->actionCode)) {
            return $fileName;
        } else {
            return "protected/views/commons/forbidden.php";
        }
    }

    public final function renderPartial($view, $params = []) {
        $fileLocation = $this->generateFileName($view);
        extract($params);
        if (file_exists($fileLocation)) {
            include_once $fileLocation;
        } else {
            $this->viewWarningPage('Included File Does Not Exist', 'The defined file to be rendered cannot be found.');
        }
    }

    public final function renderAjaxJsonResponse(array $response) {
        echo json_encode($response);
    }

    public final function redirect($url) {
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

    public final function viewErrorPage($exception) {
        $this->layout = "simple";
        $this->render('commons/error', array('exception' => $exception));
    }

    public final function viewWarningPage($header, $message) {
        $this->renderPartial('commons/warning', array('header' => $header, 'message' => $message));
    }

    protected final function checkAuthorization() {
        if (empty($this->getSessionData('employee')) || empty($this->getSessionData('user'))) {
            $this->setSessionData('notif', array('message' => "Please enter your user credentials to continue."));
            $this->redirect(array('site/login'));
        }
    }

    protected final function logRevision($revisionType, $module, $referenceId, Model $model, Model $oldModel = null) {
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

    protected final function logCustomRevision($revisionType, $module, $referenceId, $notes) {
        $this->loggingService = new RevisionHistoryLoggingService();

        $revision = new RevisionHistory();
        $revision->employee = new Employee();
        $revision->employee->id = $_SESSION['employee'];
        $revision->module = $module;
        $revision->referenceId = $referenceId;
        $revision->revisionType = $revisionType;
        $revision->notes = $notes;

        $this->loggingService->logCustomAction($revision);
    }

    protected final function remoteValidateModel(Model $model) {
        if (!$model->validate()) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $model->validationMessages));
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    protected final function validatePostData(array $keyNames, $remote = false) {
        $counter = 0;
        $data = filter_input_array(INPUT_POST);
        if (is_null($data) || empty($data)) {
            throw new ControllerException("Parameter/s are needed to process this request");
        }

        foreach ($keyNames as $key) {
            if (!array_key_exists($key, $data)) {
                $counter++;
            }
        }

        if ($counter > 0) {
            throw new ControllerException("Parameter/s are needed to process this request");
        }
    }

    public final function getFormData($indexName) {
        return filter_input_array(INPUT_POST)[$indexName];
    }

    public final function getArgumentData($argumentName) {
        return htmlentities(filter_input(INPUT_GET, $argumentName));
    }

    public final function getServerData($argumentName) {
        return filter_input(INPUT_SERVER, $argumentName);
    }

    public final function getSessionData($key) {
        return (isset($_SESSION[$key]) && !empty($_SESSION[$key])) ? $_SESSION[$key] : "";
    }

    public final function setSessionData($key, $value) {
        $_SESSION[$key] = $value;
    }

    protected final function unsetSessionData($key) {
        unset($_SESSION[$key]);
    }

}
