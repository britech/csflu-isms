<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

class ScorecardController extends Controller {

    private $mapService;
    private $scorecardService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->layout = 'column-2';
    }

    public function manage($map) {
        if (!isset($map) || empty($map)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }
        $strategyMap = $this->loadMapModel($map);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Scorecard';
        $this->render('measure-profile/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Scorecard' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Components',
                    'links' => array(
                        'Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                        'Scorecard Infrastructure' => array('scorecard/infra', 'map' => $strategyMap->id)
                    )
                )
            ),
            'map' => $strategyMap->id
        ));
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
                switch ($leadMeasure->measureProfileEnvironmentStatus) {
                    case MeasureProfile::STATUS_ACTIVE:
                        $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View') . '&nbsp;|&nbsp;' . ApplicationUtils::generateLink(array('measure/updateMovement', 'id' => $leadMeasure->id), 'Update Movement');
                        break;

                    case MeasureProfile::STATUS_DROPPED:
                        $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View');
                        break;
                }
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

    private function loadMapModel($id) {
        $map = $this->mapService->getStrategyMap($id);

        if (is_null($map)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        } else {
            return $map;
        }
    }

}
