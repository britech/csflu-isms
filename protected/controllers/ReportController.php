<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl;
use org\csflu\isms\service\alignment\StrategyAlignmentServiceSimpleImpl;
use org\csflu\isms\models\indicator\LeadOffice;

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

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
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

        $setters = array();
        $owners = array();
        $trackers = array();

        foreach ($measureProfile->leadOffices as $leadOffice) {
            $positions = explode("/", $leadOffice->designation);

            if (in_array(LeadOffice::RESPONSBILITY_TRACKER, $positions)) {
                $trackers = array_merge($trackers, array("- {$leadOffice->department->name}"));
            }

            if (in_array(LeadOffice::RESPONSIBILITY_ACCOUNTABLE, $positions)) {
                $owners = array_merge($owners, array("- {$leadOffice->department->name}"));
            }

            if (in_array(LeadOffice::RESPONSIBILITY_SETTER, $positions)) {
                $setters = array_merge($setters, array("- {$leadOffice->department->name}"));
            }
        }

        $baselineYears = array();
        $targetYears = array();
        foreach ($measureProfile->indicator->baselineData as $baseline) {
            if (!in_array($baseline->coveredYear, $baselineYears)) {
                $baselineYears = array_merge($baselineYears, array($baseline->coveredYear));
            }
        }

        foreach ($measureProfile->targets as $target) {
            if (!in_array($target->coveredYear, $targetYears)) {
                $targetYears = array_merge($targetYears, array($target->coveredYear));
            }
        }

        $dataGroups = array();
        foreach ($measureProfile->targets as $target) {
            if (!in_array($target->dataGroup, $dataGroups)) {
                $dataGroups = array_merge($dataGroups, array($target->dataGroup));
            }
        }

        $this->render('report/measure-profile', array(
            'measureProfile' => $measureProfile,
            'strategyMap' => $strategyMap,
            'setters' => nl2br(implode("\n", $setters)),
            'owners' => nl2br(implode("\n", $owners)),
            'trackers' => nl2br(implode("\n", $trackers)),
            'baselineYears' => $baselineYears,
            'targetYears' => $targetYears,
            'dataGroups' => $dataGroups,
            'baselineCount' => count($baselineYears),
            'targetsCount' => count($targetYears),
            'dataSource' => nl2br(implode("\n", explode("+", $measureProfile->indicator->dataSource)))
        ));
    }

}
