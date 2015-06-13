<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;

/**
 * 
 *
 * @author britech
 */
class MapController extends Controller {

    private $mapService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->isRbacEnabled = true;
        $this->moduleCode = ModuleAction::MODULE_SMAP;
        $this->actionCode = "SMAPM";
        $this->layout = 'column-2';
        $this->mapService = new StrategyMapService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index() {
        $this->actionCode = "SMAPV";
        $this->title = ApplicationConstants::APP_NAME . ' - Strategy Map Directory';
        $this->render('map/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Create a Strategy Map' => array('map/create')))),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function renderStrategyMapTable() {
        $maps = $this->mapService->listStrategyMaps();

        $data = array();
        foreach ($maps as $map) {
            array_push($data, array(
                'name' => ApplicationUtils::generateLink(array('map/view', 'id' => $map->id), $map->name) . '<br/><i>' . $map->visionStatement . '</i>',
                'type' => StrategyMap::getStrategyTypes()[$map->strategyType],
                'status' => StrategyMap::getEnvironmentStatusTypes()[$map->strategyEnvironmentStatus]));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create() {
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Create a Strategy Map';
        $this->render('map/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Create a Strategy Map' => 'active'),
            'model' => new StrategyMap(),
            'strategyTypes' => StrategyMap::getStrategyTypes(),
            'statusTypes' => StrategyMap::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('StrategyMap'));
        $strategyMapData = $this->getFormData('StrategyMap');
        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));
        if (!$strategyMap->validate()) {
            $this->setSessionData('validation', $strategyMap->validationMessages);
            $this->redirect(array('map/create'));
        } else {
            $id = $this->mapService->insert($strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $id, $strategyMap);
            $this->redirect(array('map/view', 'id' => $id));
        }
    }

    public function view($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $this->actionCode = "SMAPV";
        $strategyMap = $this->mapService->getStrategyMap($id);
        if (is_null($strategyMap->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Strategy Map not found');
            $this->redirect(array('map/index'));
        }

        $this->title = ApplicationConstants::APP_NAME . ' - Strategy Map';
        $this->render('map/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Entry Data' => array('map/update', 'id' => $strategyMap->id),
                        'Manage Perspectives' => array('perspective/manage', 'map' => $id),
                        'Manage Strategic Themes' => array('theme/manage', 'map' => $id),
                        'Manage Objectives' => array('objective/manage', 'map' => $strategyMap->id),
                        'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                        'Manage Initiatives' => array('initiative/index', 'map' => $strategyMap->id),
                        'Manage Unit Breakthroughs' => array('ubt/index', 'map' => $strategyMap->id)))),
            'strategyMap' => $strategyMap,
            'perspectives' => $this->mapService->listPerspectives($strategyMap),
            'themes' => $this->mapService->listThemes($strategyMap),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('StrategyMap'));
            $this->processUpdate();
        } elseif (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $strategyMap = $this->loadModel($id);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Update Entry Data';
        $this->layout = 'column-1';
        $this->render('map/create', array(
            'breadcrumb' => array('Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Update Entry Data' => 'active'),
            'model' => $strategyMap,
            'strategyTypes' => StrategyMap::getStrategyTypes(),
            'statusTypes' => StrategyMap::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function processUpdate() {
        $strategyMapData = $this->getFormData('StrategyMap');
        $strategyMap = $this->loadModel($strategyMapData['id']);
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));

        if ($strategyMap->validate()) {
            $oldMap = clone $this->loadModel($strategyMap->id);
            $strategyMap->terminationDate = $oldMap->strategyEnvironmentStatus == StrategyMap::STATUS_INACTIVE ? null : $strategyMap->terminationDate;

            if ($strategyMap->computePropertyChanges($oldMap) > 0) {
                $this->mapService->update($strategyMap);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $strategyMap, $oldMap);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Strategy Map updated'));
            }
            $this->redirect(array('map/view', 'id' => $strategyMap->id));
        } else {
            $this->setSessionData('validation', $strategyMap->validationMessages);
            $this->redirect(array('map/updateMap', 'id' => $strategyMap->id));
        }
    }

    public function validateStrategyMap() {
        try {
            $this->validatePostData(array('StrategyMap'));
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        }

        $strategyMapData = $this->getFormData('StrategyMap');

        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));

        $this->remoteValidateModel($strategyMap);
    }

    private function loadModel($id) {
        $strategyMap = $this->mapService->getStrategyMap($id);

        if (is_null($strategyMap->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Strategy Map not found');
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

}
