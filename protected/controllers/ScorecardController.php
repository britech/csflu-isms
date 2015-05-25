<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\MeasureProfileMovement;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\alignment\StrategyAlignmentServiceSimpleImpl;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

class ScorecardController extends Controller {

    private $scorecardService;
    private $alignmentService;
    private $modelLoaderUtil;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->scorecardService = new ScorecardManagementService();
        $this->alignmentService = new StrategyAlignmentServiceSimpleImpl();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->layout = 'column-2';
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
            'unitBreakthroughs'=>$this->alignmentService->listAlignedUnitBreakthroughs($strategyMap, null, $measureProfile)
        ));
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
