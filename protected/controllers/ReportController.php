<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\controllers\support\WigSessionControllerSupport;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl;
use org\csflu\isms\service\alignment\StrategyAlignmentServiceSimpleImpl;

/**
 * Description of ReportController
 *
 * @author britech
 */
class ReportController extends Controller {

    private $logger;
    private $initiativeService;
    private $alignmentService;
    private $modelLoaderUtil;
    private $wigSessionControllerSupport;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->wigSessionControllerSupport = WigSessionControllerSupport::getInstance($this);
        $this->initiativeService = new InitiativeManagementServiceSimpleImpl();
        $this->alignmentService = new StrategyAlignmentServiceSimpleImpl();
    }

    public function initiativeUpdate($id, $period) {
        $date = "{$period}-1";
        $periodDate = \DateTime::createFromFormat('Y-m-d', $date);
        $initiative = $this->modelLoaderUtil->loadInitiativeModel($id);
        $initiative->phases = $initiative->filterPhases($periodDate);

        if (count($initiative->phases) == 0) {
            $this->setSessionData('notif', array('message' => 'Update Report cannot be generated'));
            $this->redirect(array('activity/index', 'initiative' => $initiative->id, 'period' => $period));
        }

        $teams = "";
        foreach ($initiative->implementingOffices as $implementingOffice) {
            $teams.="-&nbsp;{$implementingOffice->department->name}\n";
        }

        $this->render('report/initiative-update', array(
            'period' => $periodDate,
            'initiative' => $initiative,
            'teams' => nl2br($teams),
            'beneficiaries' => implode(', ', explode('+', $initiative->beneficiaries))
        ));
    }

    public function initiativeDetail($id) {
        $initiative = $this->modelLoaderUtil->loadInitiativeModel($id);

        if (count($initiative->phases) < 1) {
            $this->setSessionData('notif', array('message' => 'Cannot Generate: Program of Work'));
            $this->redirect(array('initiative/view', 'id' => $initiative->id));
        }

        $measures = "";
        foreach ($initiative->leadMeasures as $leadMeasures) {
            $measures.="-&nbsp;{$leadMeasures->indicator->description}\n";
        }

        $objectives = "";
        foreach ($initiative->objectives as $objective) {
            $objectives.="-&nbsp;{$objective->description}\n";
        }

        $teams = "";
        foreach ($initiative->implementingOffices as $implementingOffice) {
            $teams.="-&nbsp;{$implementingOffice->department->name}\n";
        }

        $this->render('report/initiative-pow', array(
            'initiative' => $initiative,
            'measures' => nl2br($measures),
            'objectives' => nl2br($objectives),
            'beneficiaries' => implode(', ', explode('+', $initiative->beneficiaries)),
            'teams' => nl2br($teams),
            'advisers' => nl2br(implode("\n", explode('+', $initiative->advisers)))
        ));
    }

    public function scorecardUpdate($measure, $period) {
        $date = ApplicationUtils::generateEndingPeriodDate(\DateTime::createFromFormat('Y-m-d', "{$period}-1"));
        $measureProfile = $this->modelLoaderUtil->loadMeasureProfileModel($measure);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, $measureProfile->objective);

        $this->render('report/scorecard-update', array(
            'period' => $date,
            'strategyMap' => $strategyMap,
            'measureProfile' => $measureProfile,
            'initiatives' => $this->alignmentService->listAlignedInitiatives($strategyMap, null, $measureProfile),
            'unitBreakthroughs' => $this->alignmentService->listAlignedUnitBreakthroughs($strategyMap, null, $measureProfile)
        ));
    }

    public function measureProfile($id) {
        $measureProfile = $this->modelLoaderUtil->loadMeasureProfileModel($id);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, $measureProfile->objective);

        $baselineYears = $measureProfile->indicator->getBaselineYears();
        $targetYears = $measureProfile->getTargetYears();

        $this->render('report/measure-profile', array(
            'measureProfile' => $measureProfile,
            'strategyMap' => $strategyMap,
            'baselineYears' => $baselineYears,
            'targetYears' => $targetYears,
            'baselineCount' => count($baselineYears),
            'targetsCount' => count($targetYears)
        ));
    }

    public function scorecardTemplate($map) {
        $strategyMap = $this->modelLoaderUtil->loadMapModel($map);

        $startingTargetYear = intval($strategyMap->startingPeriodDate->format('Y'));
        $endingTargetYear = intval($strategyMap->endingPeriodDate->format('Y'));
        $targetYears = array();
        for ($i = $startingTargetYear; $i <= $endingTargetYear; $i++) {
            $targetYears = array_merge($targetYears, array($i));
        }

        $baselineYears = array(
            $startingTargetYear - 2,
            $startingTargetYear - 1
        );

        $perspectives = array();
        foreach ($strategyMap->objectives as $objective) {
            if (!in_array($objective->perspective, $perspectives)) {
                $perspectives = array_merge($perspectives, array($objective->perspective));
            }
        }

        $this->render('report/scorecard-template', array(
            'strategyMap' => $strategyMap,
            'alignmentService' => $this->alignmentService,
            'baselineYears' => $baselineYears,
            'targetYears' => $targetYears,
            'baselineCount' => count($baselineYears),
            'targetsCount' => count($targetYears),
            'perspectives' => $perspectives
        ));
    }

    public function wigMeeting($id) {
        $wigSession = $this->modelLoaderUtil->loadWigSessionModel($id);
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel(null, null, $wigSession);

        $this->render('report/wig-meeting', array(
            'accounts' => $this->wigSessionControllerSupport->listEmployees(),
            'collatedCommitments' => $this->wigSessionControllerSupport->collateCommitments($wigSession),
            'wigData' => $wigSession,
            'ubtData' => $unitBreakthrough,
            'timeDifference' => $wigSession->wigMeeting->meetingTimeStart->diff($wigSession->wigMeeting->meetingTimeEnd)
        ));
    }

}
