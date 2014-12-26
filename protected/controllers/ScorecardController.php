<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;

class ScorecardController extends Controller {

    private $mapService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapManagementService();
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
            )
        ));
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
