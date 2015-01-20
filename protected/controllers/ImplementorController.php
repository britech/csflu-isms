<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;

/**
 * Description of ImplementorController
 *
 * @author britech
 */
class ImplementorController extends Controller {

    private $logger;
    private $initiativeService;
    private $mapService;
    private $departmentService;

    public function __construct() {
        $this->checkAuthorization();
        $this->initiativeService = new InitiativeManagementService();
        $this->mapService = new StrategyMapManagementService();
        $this->departmentService = new DepartmentService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($initiative) {
        $data = $this->loadModel($initiative);
        $strategyMap = $this->loadMapModel($data);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Implementing Offices";
        $this->render('initiative/implem-offices', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Manage Initiative' => array('initiative/manage', 'id' => $data->id),
                'Manage Implementing Offices' => 'active'
            ),
            'model' => new ImplementingOffice(),
            'departmentModel' => new Department(),
            'initiativeModel' => $data,
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function listOffices() {
        $this->validatePostData(array('initiative'));

        $id = $this->getFormData('initiative');
        $initiative = $this->loadModel($id);

        $data = array();
        foreach ($initiative->implementingOffices as $implementingOffice) {
            array_push($data, array(
                'id' => $implementingOffice->id,
                'office' => $implementingOffice->department->name,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$implementingOffice->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function link() {
        $this->validatePostData(array('Initiative', 'Department'));

        $initiativeData = $this->getFormData('Initiative');
        $departmentData = $this->getFormData('Department');
        $initiative = new Initiative();
        $initiative->bindValuesUsingArray(array(
            'initiative' => $initiativeData,
            'implementingOffices' => $departmentData
        ));

        if (count($initiative->implementingOffices) == 0) {
            $this->setSessionData('validation', "Implementing offices should be defined");
            $this->redirect(array('implementor/index', 'initiative' => $initiative->id));
        }

        $purifiedInitiative = $this->purifyInput($initiative);
        try {
            $officeToLog = $this->initiativeService->addImplementingOffices($purifiedInitiative);
            foreach ($officeToLog as $office) {
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $purifiedInitiative->id, $office);
            }
            $this->setSessionData('notif', array('class' => 'success', 'message' => 'Implementing Office/s added'));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->redirect(array('implementor/index', 'initiative' => $purifiedInitiative->id));
    }

    public function unlink() {
        try {
            $this->validatePostData(array('id', 'initiative'));
        } catch (ControllerException $ex) {
            $this->setSessionData('notif', array('message' => $ex->getMessage(), 'class' => 'error'));
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
            return;
        }

        $id = $this->getFormData('id');
        $initiativeId = $this->getFormData('initiative');
        $initiative = $this->initiativeService->getInitiative($initiativeId);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
        } else {
            $this->setSessionData('notif', array('message' => 'Implementing Office unlinked in the Initiative'));
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('implementor/index', 'initiative' => $initiative->id))));
        }
    }

    private function purifyInput(Initiative $initiative) {
        $data = array();
        foreach ($initiative->implementingOffices as $implementingOffice) {
            $implementingOffice->department = $this->departmentService->getDepartmentDetail(array(
                'id' => $implementingOffice->department->id
            ));
            array_push($data, $implementingOffice);
        }
        $initiative->implementingOffices = $data;
        return $initiative;
    }

    private function loadModel($id) {
        $initiative = $this->initiativeService->getInitiative($id);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            $this->redirect(array('map/index'));
        }
        return $initiative;
    }

    private function loadMapModel(Initiative $initiative) {
        $strategyMap = $this->mapService->getStrategyMap(null, null, null, null, $initiative);
        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $strategyMap;
    }

}
