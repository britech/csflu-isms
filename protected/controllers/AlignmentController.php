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
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;

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
    private $ubtService;

    public function __construct() {
        $this->checkAuthorization();
        $this->isRbacEnabled = true;
        $this->initiativeService = new InitiativeManagementService();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->ubtService = new UnitBreakthroughManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function manageInitiative($id) {
        $this->moduleCode = ModuleAction::MODULE_INITIATIVE;
        $this->actionCode = "INIM";
        $initiative = $this->loadInitiativeModel($id);
        $strategyMap = $this->loadMapModel($initiative);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Strategy Alignments";
        $this->render('initiative/alignment', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Manage Initiative' => array('initiative/view', 'id' => $initiative->id),
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

    public function unlinkLeadMeasure() {
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

    public function manageUnitBreakthrough($id) {
        $this->moduleCode = ModuleAction::MODULE_UBT;
        $this->actionCode = "UBTM";
        $unitBreakthrough = $this->loadUnitBreakthroughModel($id);
        $strategyMap = $this->loadMapModel(null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Strategy Alignments";
        $this->render('ubt/alignment', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'About Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Manage Strategy Alignments' => 'active'
            ),
            'model' => $unitBreakthrough,
            'measureModel' => new MeasureProfile(),
            'objectiveModel' => new Objective(),
            'mapModel' => $strategyMap,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function listUbtObjectiveAlignment() {
        $this->validatePostData(array('ubt'));
        $id = $this->getFormData('ubt');

        $unitBreakthrough = $this->loadUnitBreakthroughModel($id);
        $data = array();
        foreach ($unitBreakthrough->objectives as $objective) {
            array_push($data, array(
                'objective' => $objective->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-objective-{$objective->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function listUbtIndicatorAlignment() {
        $this->validatePostData(array('ubt'));
        $id = $this->getFormData('ubt');

        $unitBreakthrough = $this->loadUnitBreakthroughModel($id);
        $data = array();
        foreach ($unitBreakthrough->measures as $measure) {
            array_push($data, array(
                'indicator' => $measure->indicator->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-measure-{$measure->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function insertUbtAlignment() {
        $this->validatePostData(array('Objective', 'MeasureProfile', 'UnitBreakthrough'));

        $objectiveData = $this->getFormData('Objective');
        $measureProfileData = $this->getFormData('MeasureProfile');
        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');

        $unitBreakthrough = new UnitBreakthrough();
        $unitBreakthrough->bindValuesUsingArray(array(
            'objectives' => $objectiveData,
            'measures' => $measureProfileData,
            'unitbreakthrough' => $unitBreakthroughData
        ));

        if (count($unitBreakthrough->objectives) == 0 && count($unitBreakthrough->measures) == 0) {
            $this->setSessionData('validation', array('An Objective or Measure Profile should be selected'));
        }

        try {
            $updatedUnitBreakthrough = $this->ubtService->addAlignments($unitBreakthrough);

            foreach ($updatedUnitBreakthrough->objectives as $objective) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $updatedUnitBreakthrough->id, "[Objective linked]\n\nObjective:\t{$objective->description}");
            }

            foreach ($updatedUnitBreakthrough->measures as $measureProfile) {
                $this->logCustomRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $updatedUnitBreakthrough->id, "[Measure Profile linked]\n\nMeasure Profile:\t{$measureProfile->indicator->description}");
            }
            $this->setSessionData('notif', array('class' => 'info', 'message' => 'Strategy Alignments added'));
        } catch (ServiceException $ex) {
            $this->logger->debug($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
        }

        $this->redirect(array('alignment/manageUnitBreakthrough', 'id' => $unitBreakthrough->id));
    }

    public function unlinkUbtObjectiveAlignment() {
        try {
            $this->validatePostData(array('objective', 'ubt'));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $objectiveId = $this->getFormData('objective');
        $ubtId = $this->getFormData('ubt');

        $objective = $this->loadObjectiveModel($objectiveId, true);
        $unitBreakthrough = $this->loadUnitBreakthroughModel($ubtId, true);

        try {
            $this->ubtService->deleteAlignments($unitBreakthrough, $objective);
            $this->logCustomRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_UBT, $objective->id, "[Objective unlinked]\n\nObjective:\t{$objective->description}");
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'Objective unlinked in the Unit Breakthrough.'));
        } catch (ServiceException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('alignment/manageUnitBreakthrough', 'id' => $unitBreakthrough->id))));
    }

    public function unlinkUbtMeasureProfileAlignment() {
        try {
            $this->validatePostData(array('measureProfile', 'ubt'));
        } catch (ControllerException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $measureProfileId = $this->getFormData('measureProfile');
        $ubtId = $this->getFormData('ubt');

        $measureProfile = $this->loadMeasureProfileModel($measureProfileId, true);
        $unitBreakthrough = $this->loadUnitBreakthroughModel($ubtId, true);

        try {
            $this->ubtService->deleteAlignments($unitBreakthrough, null, $measureProfile);
            $this->logCustomRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, "[Measure Profile unlinked]\n\nMeasure Profile:\t{$measureProfile->indicator->description}");
            $this->setSessionData('notif', array('class' => 'error', 'message' => 'Measure Profile unlinked in the Unit Breakthrough'));
        } catch (ServiceException $ex) {
            $this->logger->warn($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('alignment/manageUnitBreakthrough', 'id' => $unitBreakthrough->id))));
    }

    private function loadMapModel(Initiative $initiative = null, UnitBreakthrough $unitBreakthrough = null) {
        $map = $this->mapService->getStrategyMap(null, null, null, null, $initiative, $unitBreakthrough);
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

    private function loadMeasureProfileModel($id, $remote = false) {
        $measureProfile = $this->scorecardService->getMeasureProfile($id);
        if (is_null($measureProfile->id)) {
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

    private function loadUnitBreakthroughModel($id, $remote = false) {
        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($id);
        if (is_null($unitBreakthrough->id)) {
            $url = array('map/index');
            $this->setSessionData('notif', array('message' => 'Linked Lead Measure not found'));
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
                return;
            } else {
                $this->redirect($url);
            }
        }
        return $unitBreakthrough;
    }

}
