<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
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

}
