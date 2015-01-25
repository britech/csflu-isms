<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

/**
 * Description of AlignmentController
 *
 * @author britech
 */
class AlignmentController extends Controller {

    private $logger;
    private $initiativeService;
    private $mapService;
    private $scorecardService;

    public function __construct() {
        $this->checkAuthorization();
        $this->initiativeService = new InitiativeManagementService();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function manageInitiative($id) {
        $initiative = $this->loadInitiativeModel($id);
        $strategyMap = $this->loadMapModel($initiative);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Strategy Alignments";
        $this->render('initiative/alignment', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Manage Initiative' => array('initiative/manage', 'id' => $initiative->id),
                'Manage Strategy Alignments' => 'active'
            ),
            'measureModel' => new MeasureProfile(),
            'objectiveModel' => new Objective(),
            'initiativeModel' => $initiative,
            'mapModel' => $strategyMap,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function insertInitiativeAlignment() {
        $this->validatePostData(array('Objective', 'MeasureProfile', 'Initiative'));

        $objectiveData = $this->getFormData('Objective');
        $measureData = $this->getFormData('MeasureProfile');
        $initiativeData = $this->getFormData('Initiative');

        $initiative = new Initiative();
        $initiative->bindValuesUsingArray(array(
            'objectives' => $objectiveData,
            'leadMeasures' => $measureData,
            'initiative' => $initiativeData
        ));

        if (count($initiative->objectives) == 0 && count($initiative->leadMeasures) == 0) {
            $this->setSessionData('validation', array("An Objective or Measure should be selected"));
        }
        try {
            $updatedInitiative = $this->initiativeService->addAlignments($initiative);
            foreach ($updatedInitiative->objectives as $objective) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $updatedInitiative->id, "[Objective linked]\n\nObjective:\t{$objective->description}");
            }
            foreach ($updatedInitiative->leadMeasures as $leadMeasure) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_INITIATIVE, $updatedInitiative->id, "[LeadMeasure linked]\n\nIndicator:\t{$leadMeasure->indicator->description}");
            }
            $this->setSessionData('notif', array('class' => 'success', 'message' => 'Alignment added'));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->redirect(array('alignment/manageInitiative', 'id' => $initiative->id));
    }

    public function listInitiativeObjectivesAlignment() {
        $this->validatePostData(array('initiative'));
        $id = $this->getFormData('initiative');

        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->objectives as $objective) {
            array_push($data, array(
                'objective' => $objective->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$objective->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function listInitiativeIndicatorsAlignment() {
        $this->validatePostData(array('initiative'));
        $id = $this->getFormData('initiative');

        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->leadMeasures as $leadMeasure) {
            array_push($data, array(
                'indicator' => $leadMeasure->indicator->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$leadMeasure->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function unlinkObjective() {
        try {
            $this->validatePostData(array('initiative', 'objective'));
        } catch (ControllerException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
            return;
        }
        $initiativeId = $this->getFormData('initiative');
        $objectiveId = $this->getFormData('objective');

        $initiative = $this->loadInitiativeModel($initiativeId, true);
        $objective = $this->loadObjectiveModel($objectiveId, true);

        try {
            $this->initiativeService->unlinkAlignments($initiative, $objective);
            $this->logCustomRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[Objective unlinked]\n\nObjective:\t{$objective->description}");
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'Objective unlinked in the Initiative'));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('alignment/manageInitiative', 'id' => $initiativeId))));
    }
    
    public function unlinkLeadMeasure(){
         try {
            $this->validatePostData(array('initiative', 'measure'));
        } catch (ControllerException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
            return;
        }
        $initiativeId = $this->getFormData('initiative');
        $measureId = $this->getFormData('measure');
        
        $initiative = $this->loadInitiativeModel($initiativeId, true);
        $measureProfile = $this->loadMeasureProfileModel($measureId, true);
        
        try {
            $this->initiativeService->unlinkAlignments($initiative, null, $measureProfile);
            $this->logCustomRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_INITIATIVE, $initiative->id, "[LeadMeasure unlinked]\n\nIndicator:\t{$measureProfile->indicator->description}");
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'Lead Measure unlinked in the Initiative'));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('alignment/manageInitiative', 'id' => $initiativeId))));
    }

    private function loadMapModel(Initiative $initiative) {
        $map = $this->mapService->getStrategyMap(null, null, null, null, $initiative);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

    private function loadInitiativeModel($id, $remote = false) {
        $initiative = $this->initiativeService->getInitiative($id);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
                return;
            } else {
                $this->redirect(array('map/index'));
            }
        }
        return $initiative;
    }

    private function loadObjectiveModel($id, $remote = false) {
        $objective = $this->mapService->getObjective($id);
        if (is_null($objective->id)) {
            $this->setSessionData('notif', array('message' => 'Linked Objective not found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
                return;
            } else {
                $this->redirect(array('map/index'));
            }
        }
        return $objective;
    }
    
    public function loadMeasureProfileModel($id, $remote = false){
        $measureProfile = $this->scorecardService->getMeasureProfile($id);
        if(is_null($measureProfile->id)){
            $this->setSessionData('notif', array('message' => 'Linked Lead Measure not found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
                return;
            } else {
                $this->redirect(array('map/index'));
            }
        }
        return $measureProfile;
    }

}
