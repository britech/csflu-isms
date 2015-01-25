<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\Phase;
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
            )
        ));
    }

    private function loadInitiativeModel($id, $remote = false) {
        $initiative = $this->initiativeService->getInitiative($id);
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
