<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ValidationException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;

/**
 * 
 *
 * @author britech
 */
class MapController extends Controller {

    private $mapService;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->mapService = new StrategyMapService();
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
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));
        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function insert() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));
        $strategyMap->validationMode = Model::VALIDATION_MODE_INITIAL;
        if (!$strategyMap->validate()) {
            $_SESSION['validation'] = $strategyMap->validationMessages;
            $this->redirect(array('map/create'));
        } else {
            $id = $this->mapService->insert($strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $id, $strategyMap);
            $this->redirect(array('map/complete', 'id' => $id));
        }
    }

    public function view() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
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
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $strategyMap = $this->loadStrategyMapModel($id);
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
            throw new ValidationException('Another parameter is needed to process this request');
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
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));
        $strategyMap->validationMode = $validationMode;

        $this->remoteValidateModel($strategyMap);
    }

    public function complete() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
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
                        'Manage Perspectives' => array('map/managePerspectives', 'map' => $id),
                        'Manage Strategic Themes' => array('map/manageThemes', 'map' => $id),
                        'Manage Objectives' => array('map/manageObjectives', 'map' => $id)
                    ))),
            'strategyMap' => $strategyMap,
            'perspectives' => $this->mapService->listPerspectives($strategyMap),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function managePerspectives() {
        $map = filter_input(INPUT_GET, 'map');

        if (!isset($map) || empty($map)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $this->title = ApplicationConstants::APP_NAME . ' - Add Perspective';
        $strategyMap = $this->loadStrategyMapModel($map);
        $this->layout = 'column-1';
        $this->render('perspective/insert', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $map),
                'Complete Strategy Map' => array('map/complete', 'id' => $map),
                'Manage Perspectives' => 'active'),
            'id' => $strategyMap->id,
            'perspectiveList' => $this->mapService->listPerspectives($strategyMap),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : "",
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function insertPerspective() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];

        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);

        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData, $strategyMap));

        $perspective->validationMode = Model::VALIDATION_MODE_INITIAL;
        if ($perspective->validate()) {
            try {
                $this->mapService->insertPerspective($perspective, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $perspective);
                $_SESSION['notif'] = array('class' => 'info', 'message' => 'Perspective added. Please check the Strategy Map now.');
            } catch (ServiceException $ex) {
                $_SESSION['validation'] = array($ex->getMessage());
            }
        } else {
            $_SESSION['validation'] = $perspective->validationMessages;
        }
        $this->redirect(array('map/managePerspectives', 'map' => $strategyMap->id));
    }

    public function updatePerspective() {
        if (count(filter_input_array(INPUT_POST)) > 0 && array_key_exists('Perspective', filter_input_array(INPUT_POST))) {
            $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];
            $this->processPerspectiveUpdate($perspectiveData);
        }

        $id = filter_input(INPUT_GET, 'id');
        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $perspective = $this->loadPerspectiveModel($id);
        $strategyMap = $this->loadStrategyMapModel('', $perspective);

        $this->title = ApplicationConstants::APP_NAME . ' - Update Perspective';
        $this->layout = 'column-1';
        $this->render('perspective/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Perspectives' => array('map/managePerspectives', 'map' => $strategyMap->id),
                'Update Perspective' => 'active'),
            'perspective' => $perspective,
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    private function processPerspectiveUpdate($perspectiveData) {
        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);
        $perspective->validationMode = Model::VALIDATION_MODE_UPDATE;

        if ($perspective->validate()) {
            try {
                $oldPerspective = clone $this->loadPerspectiveModel($perspective->id);
                $strategyMap = $this->loadStrategyMapModel('', $perspective);

                if ($perspective->computePropertyChanges($oldPerspective) > 0) {
                    $this->mapService->updatePerspective($perspective);
                    $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $perspective, $oldPerspective);
                    $_SESSION['notif'] = array('class' => 'info', 'message' => 'Perspective updated');
                }
                $this->redirect(array('map/managePerspectives', 'map' => $strategyMap->id));
            } catch (ServiceException $ex) {
                $_SESSION['validation'] = array($ex->getMessage());
                $this->redirect(array('map/updatePerspective', 'id' => $perspective->id));
            }
        } else {
            $_SESSION['validation'] = $perspective->validationMessages;
            $this->redirect(array('map/updatePerspective', 'id' => $perspective->id));
        }
    }

    public function confirmDeletePerspective() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $perspective = $this->loadPerspectiveModel($id);
        $strategyMap = $this->loadStrategyMapModel('', $perspective);
        $this->layout = "column-1";
        $this->render('commons/confirm', array(
            'confirm' => array('class' => 'error',
                'header' => 'Confirm Perspective deletion',
                'text' => "Do you want to delete this perspective? Continuing will remove the perspective, <strong>{$perspective->description}</strong>, in the Strategy Map",
                'accept.class' => 'red',
                'accept.text' => 'Yes',
                'accept.url' => array('map/deletePerspective', 'id' => $id),
                'deny.class' => 'green',
                'deny.text' => 'No',
                'deny.url' => array('map/managePerspectives', 'map' => $strategyMap->id)),
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Perspectives' => array('map/managePerspectives', 'map' => $strategyMap->id),
                'Delete Perspective' => 'active')));
    }

    public function deletePerspective() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $perspective = $this->clonePerspective($id);
        $strategyMap = $this->mapService->getStrategyMap('', $perspective);
        $this->mapService->deletePerspective($id);
        $_SESSION['notif'] = array('class' => '', 'message' => 'Perspective removed from the Strategy Map');
        $this->redirect(array('map/managePerspectives', 'map' => $strategyMap->id));
    }

    /**
     * AJAX validation
     */
    public function validatePerspective() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            $this->renderAjaxJsonResponse(array('respCode' => '50'));
        }

        $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];
        $mode = filter_input_array(INPUT_POST)['mode'];

        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);
        $perspective->validationMode = $mode;

        $this->remoteValidateModel($perspective);
    }

    public function listEnlistedPerspectives() {
        $perspectives = $this->mapService->listPerspectives();

        $perspectiveArray = array();

        foreach ($perspectives as $perspective) {
            array_push($perspectiveArray, array('description' => $perspective->description));
        }
        $this->renderAjaxJsonResponse($perspectiveArray);
    }

    private function loadStrategyMapModel($id = null, $perspective = null, $objective = null, $theme = null) {
        $strategyMap = $this->mapService->getStrategyMap($id, $perspective, $objective, $theme);

        if (is_null($strategyMap->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Strategy Map not found');
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

    private function loadPerspectiveModel($id) {
        $perspective = $this->mapService->getPerspective($id);

        if (is_null($perspective->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Perspective not found');
            $this->redirect(array('map/index'));
        } else {
            return $perspective;
        }
    }

}
