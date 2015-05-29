<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
use org\csflu\isms\models\indicator\MeasureProfileMovementLog;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\controllers\support\ScorecardControllerSupport;
use org\csflu\isms\service\alignment\StrategyAlignmentServiceSimpleImpl;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

class ScorecardController extends Controller {

    private $scorecardService;
    private $alignmentService;
    private $modelLoaderUtil;
    private $controllerSupport;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->scorecardService = new ScorecardManagementService();
        $this->alignmentService = new StrategyAlignmentServiceSimpleImpl();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->controllerSupport = ScorecardControllerSupport::getInstance($this);
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listLeadMeasures() {
        $this->validatePostData(array('map', 'readonly'));
        $map = $this->getFormData('map');
        $readOnly = boolval($this->getFormData('readonly'));
        $strategyMap = $this->loadMapModel($map);

        $leadMeasures = $this->scorecardService->listMeasureProfiles($strategyMap);
        $data = array();
        foreach ($leadMeasures as $leadMeasure) {

            if ($readOnly) {
                $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View');
            } else {
                $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View') . " | " . ApplicationUtils::generateLink('#', 'Manage Movements', array('id' => "movement-{$leadMeasure->id}"));
            }

            array_push($data, array(
                'id' => $leadMeasure->id,
                'perspective' => $leadMeasure->objective->perspective->positionOrder . '&nbsp;-&nbsp;' . $leadMeasure->objective->perspective->description,
                'objective' => $leadMeasure->objective->description,
                'indicator' => $leadMeasure->indicator->description,
                'action' => $actions
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function movements($measure, $period) {
        $periodDate = \DateTime::createFromFormat('Y-m-d', "{$period}-1");
        $measureProfile = $this->loadMeasureProfileModel($measure);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);

        $this->layout = "column-2";
        $this->title = ApplicationConstants::APP_NAME . ' - Scorecard Movements';
        $this->render('scorecard/index', array(
            self::COMPONENT_BREADCRUMB => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Manage Movements' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'scorecard/_index-navi'
            ),
            'measureProfile' => $measureProfile,
            'period' => $periodDate,
            'model' => $this->resolveMovementModel($measureProfile, $periodDate),
            'initiatives' => $this->alignmentService->listAlignedInitiatives($strategyMap, null, $measureProfile),
            'unitBreakthroughs' => $this->alignmentService->listAlignedUnitBreakthroughs($strategyMap, null, $measureProfile),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function enlistMovement($measure, $period) {
        $measureProfile = $this->loadMeasureProfileModel($measure);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $model = new MeasureProfileMovement();
        $model->periodDate = "{$period}-1";
        $this->render('scorecard/enlist', array(
            self::COMPONENT_BREADCRUMB => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Manage Movements' => array('scorecard/movements', 'measure' => $measureProfile->id, 'period' => $period),
                'Enlist Movement' => 'active'
            ),
            'movementModel' => $model,
            'movementLogModel' => new MeasureProfileMovementLog(),
            'measureProfileModel' => $measureProfile,
            'period' => \DateTime::createFromFormat('Y-m-d', $model->periodDate)
        ));
    }

    public function validateMovementInput() {
        try {
            $this->validatePostData(array('MeasureProfileMovement', 'MeasureProfileMovementLog'));

            $measureProfileMovement = $this->controllerSupport->constructMovementEntity();
            $measureProfileMovementLog = $this->controllerSupport->constructMovementLogEntity();

            $measureProfileMovement->validate();
            $measureProfileMovementLog->validate();
            $validationMessages = array_merge($measureProfileMovement->validationMessages, $measureProfileMovementLog->validationMessages);

            if (count($validationMessages) == 0) {
                $this->renderAjaxJsonResponse(array('respCode' => '00'));
            } else {
                $this->viewWarningPage('Validation error/s. Please check your entries', nl2br(implode("\n", $validationMessages)));
            }
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        }
    }

    public function insertMovement() {
        $this->validatePostData(array('MeasureProfileMovement', 'MeasureProfileMovementLog', 'MeasureProfile'));

        $id = $this->getFormData('MeasureProfile')['id'];
        $measureProfile = $this->loadMeasureProfileModel($id);

        $movementLog = $this->controllerSupport->constructMovementLogEntity();
        $movement = $this->controllerSupport->constructMovementEntity();
        $movement->movementLogs = array($movementLog);

        $movementLog->validate();
        $movement->validate();
        $validationMessages = array_merge($movement->validationMessages, $movementLog->validationMessages);

        if (count($validationMessages) > 0) {
            $this->setSessionData('validation', $validationMessages);
            $this->redirect(array('scorecard/enlistMovement', 'measure' => $measureProfile->id, 'period' => $movement->periodDate->format('Y-m')));
            return;
        }

        $this->scorecardService->enlistMovement($measureProfile, $movement);
        $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $measureProfile->id, $movement);
        $this->setSessionData('notif', array('class' => 'success', 'message' => 'Movement successfully logged'));
        $this->redirect(array('scorecard/movements', 'measure' => $measureProfile->id, 'period' => $movement->periodDate->format('Y-m')));
    }

    public function updateMovement($measure = null, $period = null) {
        if (is_null($measure) && is_null($period)) {
            $this->validatePostData(array('MeasureProfileMovement', 'MeasureProfileMovementLog', 'MeasureProfile'));
            $this->processMovementUpdate();
        }

        $measureProfile = $this->loadMeasureProfileModel($measure);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $movement = new MeasureProfileMovement();
        $movement->periodDate = "{$period}-1";
        $this->render('scorecard/update', array(
            self::COMPONENT_BREADCRUMB => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Manage Movements' => array('scorecard/movements', 'measure' => $measureProfile->id, 'period' => $period),
                'Update Movement' => 'active'
            ),
            'movementModel' => $movement,
            'movementLogModel' => new MeasureProfileMovementLog(),
            'measureProfileModel' => $measureProfile,
            'period' => \DateTime::createFromFormat('Y-m-d', "{$period}-1")
        ));
    }

    private function processMovementUpdate() {
        $id = $this->getFormData('MeasureProfile')['id'];
        $measureProfile = $this->loadMeasureProfileModel($id);

        $movementLog = $this->controllerSupport->constructMovementLogEntity();
        $movement = $this->controllerSupport->constructMovementEntity();
        $movement->movementLogs = array($movementLog);
        $oldModel = $this->resolveMovementModel($measureProfile, $movement->periodDate);

        $movementLog->validate();
        $movement->validate();
        $validationMessages = array_merge($movement->validationMessages, $movementLog->validationMessages);

        if (count($validationMessages) > 0) {
            $this->setSessionData('validation', $validationMessages);
            $this->redirect(array('scorecard/updateMovement', 'measure' => $measureProfile->id, 'period' => $movement->periodDate->format('Y-m')));
            return;
        }
        
        if ($movement->computePropertyChanges($oldModel) > 0) {
            $this->scorecardService->updateMovement($measureProfile, $movement);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $measureProfile->id, $movement);
            $this->setSessionData('notif', array('class' => 'info', 'message' => 'Movement successfully updated'));
        }
        $this->redirect(array('scorecard/movements', 'measure' => $measureProfile->id, 'period' => $movement->periodDate->format('Y-m')));
    }

    private function resolveMovementModel(MeasureProfile $measureProfile, \DateTime $period) {
        foreach ($measureProfile->movements as $movement) {
            if ($movement->periodDate == $period) {
                return $movement;
            }
        }
        return new MeasureProfileMovement();
    }

    private function loadMapModel($id = null, Objective $objective = null) {
        return $this->modelLoaderUtil->loadMapModel($id, null, $objective);
    }

    private function loadMeasureProfileModel($id) {
        return $this->modelLoaderUtil->loadMeasureProfileModel($id);
    }

}
