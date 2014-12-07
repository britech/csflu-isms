<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
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
        $this->layout = 'column-2';
        $this->mapService = new StrategyMapService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index() {
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
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('StrategyMap'));
        $strategyMapData = $this->getFormData('StrategyMap');
        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));
        $strategyMap->validationMode = Model::VALIDATION_MODE_INITIAL;
        if (!$strategyMap->validate()) {
            $this->setSessionData('validation', $strategyMap->validationMessages);
            $this->redirect(array('map/create'));
        } else {
            $id = $this->mapService->insert($strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $id, $strategyMap);
            $this->redirect(array('map/complete', 'id' => $id));
        }
    }

    public function view($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $strategyMap = $this->mapService->getStrategyMap($id);
        if (is_null($strategyMap->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Strategy Map not found');
            $this->redirect(array('map/index'));
        }

        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Strategy Map';
        $this->render('map/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => 'active'),
            'strategyMap' => $strategyMap
        ));
    }

    public function updateMap() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $strategyMap = $this->loadStrategyMapModel($id);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Update Entry Data';
        $this->layout = 'column-1';
        $this->render('map/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Update Entry Data' => 'active'),
            'model' => $strategyMap,
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));
        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function update() {
        if (!(count(filter_input_array(INPUT_POST)) > 0 && array_key_exists('StrategyMap', filter_input_array(INPUT_POST)))) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $strategyMap = new StrategyMap();
        $strategyMap->validationMode = Model::VALIDATION_MODE_UPDATE;
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));

        if ($strategyMap->validate()) {
            $oldMap = clone $this->loadStrategyMapModel($strategyMap->id);

            if ($strategyMap->computePropertyChanges($oldMap) > 0) {
                $this->mapService->update($strategyMap);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $strategyMap, $oldMap);
                $_SESSION['notif'] = array('class' => 'info', 'message' => 'Strategy Map updated');
            }
            $this->redirect(array('map/complete', 'id' => $strategyMap->id));
        } else {
            $_SESSION['validation'] = $strategyMap->validationMessages;
            $this->redirect(array('map/updateMap', 'id' => $strategyMap->id));
        }
    }

    /**
     * AJAX validation
     */
    public function validateStrategyMap() {
        $condition = array_key_exists('StrategyMap', filter_input_array(INPUT_POST)) && array_key_exists('mode', filter_input_array(INPUT_POST));
        if (!(count(filter_input_array(INPUT_POST)) > 0 && $condition)) {
            $this->renderAjaxJsonResponse(array('respCode' => '50'));
        }
        $validationMode = filter_input_array(INPUT_POST)['mode'];

        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData), $strategyMap);
        $strategyMap->validationMode = $validationMode;

        $this->remoteValidateModel($strategyMap);
    }

    public function complete() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $strategyMap = $this->loadStrategyMapModel($id);
        $this->title = ApplicationConstants::APP_NAME . ' - Complete Strategy Map';
        $this->render('map/complete', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $id),
                'Complete Strategy Map' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Entry Data' => array('map/updateMap', 'id' => $id),
                        'Manage Perspectives' => array('perspective/manage', 'map' => $id),
                        'Manage Strategic Themes' => array('theme/manage', 'map' => $id),
                        'Manage Objectives' => array('objective/manage', 'map' => $id)
                    ))),
            'strategyMap' => $strategyMap,
            'perspectives' => $this->mapService->listPerspectives($strategyMap),
            'themes' => $this->mapService->listThemes($strategyMap),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));

        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    private function loadStrategyMapModel($id) {
        $strategyMap = $this->mapService->getStrategyMap($id);

        if (is_null($strategyMap->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Strategy Map not found');
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

}
