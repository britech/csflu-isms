<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;

/**
 * Description of ImplementorController
 *
 * @author britech
 */
class ImplementorController extends Controller {

    private $logger;
    private $initiativeService;
    private $mapService;

    public function __construct() {
        $this->checkAuthorization();
        $this->initiativeService = new InitiativeManagementService();
        $this->mapService = new StrategyMapManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($initiative) {
        $data = $this->loadModel($initiative);
        $strategyMap = $this->loadMapModel($data);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Implementing Offices";
        $this->render('initiative/implem-offices', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Manage Initiative' => array('initiative/manage', 'id' => $data->id),
                'Manage Implementing Offices' => 'active'
            ),
            'model' => new ImplementingOffice(),
            'departmentModel' => new Department(),
            'initiativeModel' => $data
        ));
    }

    private function loadModel($id) {
        $initiative = $this->initiativeService->getInitiative($id);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            $this->redirect(array('map/index'));
        }
        return $initiative;
    }

    private function loadMapModel(Initiative $initiative) {
        $strategyMap = $this->mapService->getStrategyMap(null, null, null, null, $initiative);
        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $strategyMap;
    }

}
