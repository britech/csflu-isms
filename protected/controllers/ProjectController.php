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
use org\csflu\isms\models\initiative\Activity;
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

    public function manageComponents($initiative) {
        $initiativeModel = $this->loadInitiativeModel($initiative);
        $strategyMap = $this->loadMapModel($initiativeModel);

        $this->title = ApplicationConstants::APP_NAME . " - Enlist Component";
        $this->render('initiative/components', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Initiative' => array('initiative/manage', 'id' => $initiativeModel->id),
                'Manage Components' => 'active'
            ),
            'model' => new Component(),
            'phaseModel' => new Phase(),
            'initiativeModel' => $initiativeModel,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function listComponents() {
        $this->validatePostData(array('initiative'));
        $id = $this->getFormData('initiative');

        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->phases as $phase) {
            foreach ($phase->components as $component) {
                array_push($data, array(
                    'id' => $component->id,
                    'phase' => "{$phase->phaseNumber} - {$phase->title}",
                    'component' => $component->description,
                    'description' => "Phase {$phase->phaseNumber} - {$component->description}",
                    'actions' => ApplicationUtils::generateLink(array('project/updateComponent', 'id' => $component->id, 'phase' => $phase->id), 'Update') . '&nbsp;|&nbsp;' . ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$component->id}-{$phase->id}"))
                ));
            }
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function insertComponent() {
        $this->validatePostData(array('Phase', 'Component'));

        $phaseData = $this->getFormData('Phase');
        $componentData = $this->getFormData('Component');

        $component = new Component($componentData['description']);
        $initiative = $this->loadInitiativeModel(null, new Phase($phaseData['id']));
        $phase = $this->loadPhaseModel($phaseData['id'], $initiative, array('url' => array('project/manageComponents', 'initiative' => $initiative->id)));

        if (is_null($phase->id) && is_null($component->description)) {
            $this->setSessionData('validation', array('-&nbsp;Component should be defined', '-&nbsp;Phase should be defined'));
        } elseif (is_null($phase->id)) {
            $this->setSessionData('validation', array('-&nbsp;Phase should be defined'));
        } elseif (is_null($component->description)) {
            $this->setSessionData('validation', array('-&nbsp;Component should be defined'));
        } else {
            try {
                $this->initiativeService->manageComponent($component, $phase);
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Component added]\n\nComponent:\t{$component->description}\nPhase:\t{$phase->title}");
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Component added'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        }
        $this->redirect(array('project/manageComponents', 'initiative' => $initiative->id));
    }

    public function updateComponent($id = null, $phase = null) {
        if (is_null($id) && is_null($phase)) {
            $this->validatePostData(array('Component', 'Phase'));
            $this->processComponentUpdate();
        }

        $initiative = $this->loadInitiativeModel(null, new Phase($phase));
        $strategyMap = $this->loadMapModel($initiative);
        $component = $this->loadComponentModel($id, new Phase($phase), array('url' => array('project/manageComponents', 'initiative' => $initiative->id)));

        $this->title = ApplicationConstants::APP_NAME . " - Enlist Component";
        $this->render('initiative/components', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Initiative' => array('initiative/manage', 'id' => $initiative->id),
                'Manage Components' => array('project/manageComponents', 'initiative' => $initiative->id),
                'Update Component' => 'active'
            ),
            'model' => $component,
            'phaseModel' => $this->loadPhaseModel($phase, $initiative),
            'initiativeModel' => $initiative,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function processComponentUpdate() {
        $phaseData = $this->getFormData('Phase');
        $componentData = $this->getFormData('Component');

        $initiative = $this->loadInitiativeModel(null, new Phase($phaseData['id']));
        $phase = $this->loadPhaseModel($phaseData['id'], $initiative);

        $component = new Component($componentData['description'], $componentData['id']);
        $oldPhase = $this->initiativeService->getPhaseByComponent(new Component(null, $componentData['id']), $initiative);
        $oldComponent = $this->loadComponentModel($componentData['id'], $oldPhase);

        if ($oldComponent->description != $component->description || $oldPhase->id != $phase->id) {
            try {
                $this->initiativeService->manageComponent($component, $phase);
                $this->logCustomRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Component updated]\n\nComponent:\t{$component->description}\nPhase:\t{$phase->description}");
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Component updated'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        }
        $this->redirect(array('project/manageComponents', 'initiative' => $initiative->id));
    }

    public function deleteComponent() {
        try {
            $this->validatePostData(array('component', 'phase'));
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $componentId = $this->getFormData('component');
        $phaseId = $this->getFormData('phase');

        $initiative = $this->loadInitiativeModel(null, new Phase($phaseId), true);
        $component = clone $this->loadComponentModel($componentId, new Phase($phaseId), array('url' => array('project/manageComponents', 'initiative' => $initiative->id), 'remote' => true));

        $this->initiativeService->deleteComponent($component->id);
        $this->logCustomRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Component deleted]\n\nComponent:{$component->description}");
        $this->setSessionData('notif', array('message' => 'Component deleted'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('project/manageComponents', 'initiative' => $initiative->id))));
    }

    public function manageActivities($initiative) {
        $initiativeModel = $this->loadInitiativeModel($initiative);
        $strategyMap = $this->loadMapModel($initiativeModel);

        $initiativeModel->startingPeriod = $initiativeModel->startingPeriod->format('Y-m-d');
        $initiativeModel->endingPeriod = $initiativeModel->endingPeriod->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . " - Enlist Activity";
        $this->render('initiative/activity-input', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Initiative' => array('initiative/manage', 'id' => $initiativeModel->id),
                'Manage Activities' => 'active'
            ),
            'model' => new Activity(),
            'componentModel' => new Component(),
            'initiativeModel' => $initiativeModel,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function validateActivityInput() {
        $this->validatePostData(array('Activity', 'Component'));

        $activityData = $this->getFormData('Activity');
        $componentData = $this->getFormData('Component');

        $activity = new Activity();
        $activity->bindValuesUsingArray(array('activity' => $activityData));
        $component = new Component(null, $componentData['id']);

        if (!$activity->validate()) {
            $data = $activity->validationMessages;
            if (strlen($component->id) < 1) {
                array_push($data, '- Component should be defined');
            }
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $data));
        } elseif (strlen($component->id) < 1) {
            $this->viewWarningPage('Validation error. Please check your entries', "- Component should be defined");
        } else {
            $this->renderAjaxJsonResponse(array('respCode' => '00'));
        }
    }

    public function insertActivity() {
        $this->validatePostData(array('Activity', 'Component', 'Initiative'));

        $activityData = $this->getFormData('Activity');
        $componentData = $this->getFormData('Component');
        $initiativeData = $this->getFormData('Initiative');

        $activity = new Activity();
        $activity->bindValuesUsingArray(array('activity' => $activityData));
        $component = new Component(null, $componentData['id']);
        
        if (!$activity->validate()) {
            $data = $activity->validationMessages;
            if (strlen($component->id) < 1) {
                array_push($data, '- Component should be defined');
            }
            $this->setSessionData('validation', $data);
        } elseif (strlen($component->id) < 1) {
            $this->setSessionData('validation', array('- Component should be defined'));
        } else {
            $this->setSessionData('notif', array('class'=>'success', 'message'=>'Activity successfully added'));
        }

        $initiative = $this->loadInitiativeModel($initiativeData['id']);
        try {
            $this->initiativeService->manageActivity($activity, $component);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $initiative->id, $activity);
            $this->setSessionData('notif', array('class'=>'success', 'message'=>'Activity successfully added.'));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->redirect(array('project/manageActivities', 'initiative' => $initiative->id));
    }

    private function loadComponentModel($id, Phase $phase, $options = array()) {
        $component = $this->initiativeService->getComponent($id, $phase);
        if (is_null($component->id)) {
            if (!array_key_exists('url', $options)) {
                throw new ControllerException("Redirection URL should be defined");
            }
            $this->setSessionData('notif', array('message' => 'Phase not found'));
            if (array_key_exists('remote', $options) && $options['remote']) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($options['url'])));
            } else {
                $this->redirect($options['url']);
            }
        }
        return $component;
    }

    private function loadPhaseModel($id, Initiative $initiative, $options = array()) {
        $phase = $this->initiativeService->getPhase($id, $initiative);
        if (is_null($phase->id)) {
            if (!array_key_exists('url', $options)) {
                throw new ControllerException("Redirection URL should be defined");
            }
            $this->setSessionData('notif', array('message' => 'Phase not found'));
            if (array_key_exists('remote', $options) && $options['remote']) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($options['url'])));
            } else {
                $this->redirect($options['url']);
            }
        }
        return $phase;
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
