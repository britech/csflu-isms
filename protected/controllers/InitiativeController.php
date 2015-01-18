<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;

/**
 * Description of InitiativeController
 *
 * @author britech
 */
class InitiativeController extends Controller {

    private $logger;
    private $mapService;
    private $initiativeService;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = new \Logger(__CLASS__);
        $this->mapService = new StrategyMapManagementService();
        $this->initiativeService = new InitiativeManagementService();
    }

    public function index($map) {
        $strategyMap = $this->loadMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Initiatives';
        $this->layout = 'column-2';
        $this->render('initiative/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Initiatives' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Create an Initiative' => array('initiative/create', 'map' => $strategyMap->id)
                    )
                )
            ),
            'map' => $strategyMap->id
        ));
    }

    public function listInitiatives() {
        $this->validatePostData(array('map'));

        $map = $this->getFormData('map');
        $strategyMap = $this->loadMapModel($map);

        $initiatives = $this->initiativeService->listInitiatives($strategyMap);
        $data = array();
        foreach ($initiatives as $initiative) {
            array_push($data, array(
                'initiative' => $initiative->title,
                'action' => ApplicationUtils::generateLink(array('initiative/manage', 'id' => $initiative->id), 'Manage')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create($map) {
        $strategyMap = $this->loadMapModel($map);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');

        $model = new Initiative();
        $model->startingPeriod = $strategyMap->startingPeriodDate;
        $model->endingPeriod = $strategyMap->endingPeriodDate;

        $this->title = ApplicationConstants::APP_NAME . ' - Create an Initiative';
        $this->render('initiative/profile', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Initiatives' => array('initiative/index', 'map' => $strategyMap->id),
                'Create an Initiative' => 'active'
            ),
            'model' => $model,
            'mapModel' => $strategyMap,
            'objectiveModel' => new Objective(),
            'measureModel' => new MeasureProfile(),
            'departmentModel' => new Department(),
            'statusTypes' => Initiative::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function loadMapModel($id) {
        $map = $this->mapService->getStrategyMap($id);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

}
