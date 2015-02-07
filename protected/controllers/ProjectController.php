<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;

/**
 * Description of ActivityController
 *
 * @author britech
 */
class ProjectController extends Controller {

    private $logger;
    private $mapService;
    private $initiativeService;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->mapService = new StrategyMapManagementService();
        $this->initiativeService = new InitiativeManagementService();
    }

    public function managePhases($initiative) {
        $initiativeModel = $this->loadInitiativeModel($initiative);
        $strategyMap = $this->loadMapModel($initiativeModel);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Phases";
        $this->render('initiative/phases', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Initiative' => array('initiative/manage', 'id' => $initiativeModel->id),
                'Manage Phases' => 'active'
            ),
            'phase' => new Phase(),
            'component' => new Component(),
            'initiative' => $initiativeModel,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function validatePhaseInput() {
        try {
            $this->validatePostData(array('Phase'));
        } catch (ControllerException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $phaseData = $this->getFormData('Phase');
        $phase = new Phase();
        $phase->bindValuesUsingArray(array(
            'phase' => $phaseData
        ));
        $this->remoteValidateModel($phase);
    }

    public function enlistPhase() {
        $this->validatePostData(array('Initiative', 'Phase'));

        $initiativeData = $this->getFormData('Initiative');
        $phaseData = $this->getFormData('Phase');

        $initiative = $this->loadInitiativeModel($initiativeData['id']);
        $phase = new Phase();
        $phase->bindValuesUsingArray(array(
            'phase' => $phaseData
        ));

        if ($phase->validate()) {
            try {
                $this->initiativeService->addPhase($phase, $initiative);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $initiative->id, $phase);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Phase successfully added to Initiative'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', $phase->validationMessages);
        }
        $this->redirect(array('project/managePhases', 'initiative' => $initiative->id));
    }

    public function listPhases() {
        $this->validatePostData(array('initiative'));

        $id = $this->getFormData('initiative');
        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->phases as $phase) {
            array_push($data, array(
                'id' => $phase->id,
                'phaseNumber' => $phase->phaseNumber,
                'phase' => "{$phase->phaseNumber} - {$phase->title}",
                'title' => $phase->title,
                'description' => $phase->description,
                'actions' => ApplicationUtils::generateLink(array('project/updatePhase', 'id' => $phase->id), 'Update') . '&nbsp|&nbsp' . ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$phase->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function updatePhase($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('Phase'));
            $this->processPhaseUpdate();
        }

        $phase = new Phase();
        $phase->id = $id;

        $initiative = $this->loadInitiativeModel(null, $phase);
        $strategyMap = $this->loadMapModel($initiative);
        $phaseModel = $this->initiativeService->getPhase($id, $initiative);
        $phaseModel->validationMode = \org\csflu\isms\core\Model::VALIDATION_MODE_UPDATE;

        $this->title = ApplicationConstants::APP_NAME . " - Update Phase";
        $this->render('initiative/phases', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Initiative' => array('initiative/manage', 'id' => $initiative->id),
                'Manage Phases' => array('project/managePhases', 'initiative' => $initiative->id),
                'Update Phase' => 'active'
            ),
            'phase' => $phaseModel,
            'initiative' => $initiative,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    private function processPhaseUpdate() {
        $phaseData = $this->getFormData('Phase');
        $initiativeData = $this->getFormData('Initiative');

        $initiative = $this->loadInitiativeModel($initiativeData['id']);

        $phase = new Phase();
        $phase->bindValuesUsingArray(array(
            'phase' => $phaseData
        ));
        $oldPhase = clone $this->initiativeService->getPhase($phase->id, $initiative);

        if ($phase->validate() && $phase->computePropertyChanges($oldPhase) > 0) {
            try {
                $this->initiativeService->updatePhase($phase);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_INITIATIVE, $initiative->id, $phase, $oldPhase);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Phase updated'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } elseif (!$phase->validate()) {
            $this->setSessionData('validation', $phase->validationMessages);
        }
        $this->redirect(array('project/managePhases', 'initiative' => $initiative->id));
    }

    public function deletePhase() {
        $this->validatePostData(array('phase'));
        $id = $this->getFormData('phase');

        $initiative = $this->loadInitiativeModel(null, new Phase($id), true);
        $phase = $this->initiativeService->getPhase($id, $initiative);

        if (is_null($phase->id)) {
            $this->setSessionData('notif', array('message' => 'Phase not found'));
        } else {
            $this->initiativeService->deletePhase($id);
            $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_INITIATIVE, $initiative->id, $phase);
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'Phase deleted'));
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('project/managePhases', 'initiative' => $initiative->id))));
    }

    private function loadInitiativeModel($id = null, Phase $phase = null, $remote = false) {
        $initiative = $this->initiativeService->getInitiative($id, $phase);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
            } else {
                $this->redirect(array('map/index'));
            }
        }
        return $initiative;
    }

    private function loadMapModel(Initiative $initiative) {
        $strategyMap = $this->mapService->getStrategyMap(null, null, null, null, $initiative);
        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            $this->redirect(array('map/index'));
        }
        return $strategyMap;
    }

}
