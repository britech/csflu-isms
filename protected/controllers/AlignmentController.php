<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl as InitiativeManagementService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;

/**
 * Description of AlignmentController
 *
 * @author britech
 */
class AlignmentController extends Controller {

    private $logger;
    private $initiativeService;
    private $mapService;

    public function __construct() {
        $this->checkAuthorization();
        $this->initiativeService = new InitiativeManagementService();
        $this->mapService = new StrategyMapManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function manageInitiative($id) {
        $initiative = $this->loadInitiativeModel($id);
        $strategyMap = $this->loadMapModel($initiative);

        $this->title = ApplicationConstants::APP_NAME . " - Manage Strategy Alignments";
        $this->render('initiative/alignment', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Initiative Directory' => array('initiative/index', 'map' => $strategyMap->id),
                'Manage Initiative' => array('initiative/manage', 'id' => $initiative->id),
                'Manage Strategy Alignments' => 'active'
            ),
            'measureModel' => new MeasureProfile(),
            'objectiveModel' => new Objective(),
            'initiativeModel' => $initiative,
            'mapModel' => $strategyMap,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function insertInitiativeAlignment() {
        $this->validatePostData(array('Objective', 'MeasureProfile', 'Initiative'));

        $objectiveData = $this->getFormData('Objective');
        $measureData = $this->getFormData('MeasureProfile');
        $initiativeData = $this->getFormData('Initiative');

        $initiative = new Initiative();
        $initiative->bindValuesUsingArray(array(
            'objectives' => $objectiveData,
            'leadMeasures' => $measureData,
            'initiative' => $initiativeData
        ));

        if (count($initiative->objectives) == 0 && count($initiative->leadMeasures) == 0) {
            $this->setSessionData('validation', array("An Objective or Measure should be selected"));
        }
        $this->setSessionData('notif', array('class' => 'success', 'message' => 'Alignment added'));
        $this->redirect(array('alignment/manageInitiative', 'id' => $initiative->id));
    }

    public function listInitiativeObjectivesAlignment() {
        $this->validatePostData(array('initiative'));
        $id = $this->getFormData('initiative');

        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->objectives as $objective) {
            array_push($data, array(
                'objective' => $objective->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$objective->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }
    
    public function listInitiativeIndicatorsAlignment() {
        $this->validatePostData(array('initiative'));
        $id = $this->getFormData('initiative');

        $initiative = $this->loadInitiativeModel($id);
        $data = array();
        foreach ($initiative->leadMeasures as $leadMeasure) {
            array_push($data, array(
                'indicator' => $leadMeasure->indicator->description,
                'action' => ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$leadMeasure->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function loadMapModel(Initiative $initiative) {
        $map = $this->mapService->getStrategyMap(null, null, null, null, $initiative);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

    private function loadInitiativeModel($id) {
        $initiative = $this->initiativeService->getInitiative($id);
        if (is_null($initiative->id)) {
            $this->setSessionData('notif', array('message' => 'Initiative not found'));
            $this->redirect(array('map/index'));
        }
        return $initiative;
    }

}
