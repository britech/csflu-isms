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
use org\csflu\isms\models\map\Theme;
use org\csflu\isms\models\map\Objective;
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
                        'Manage Perspectives' => array('perspective/manage', 'map' => $id),
                        'Manage Strategic Themes' => array('map/manageThemes', 'map' => $id),
                        'Manage Objectives' => array('map/manageObjectives', 'map' => $id)
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

    

    public function manageThemes() {
        $map = filter_input(INPUT_GET, 'map');

        if (!isset($map) || empty($map)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $strategyMap = $this->loadStrategyMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Themes';
        $this->layout = 'column-1';
        $this->render('perspective/theme', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Themes' => 'active'),
            'themes' => $this->mapService->listThemes($strategyMap),
            'model' => new Theme(),
            'mapModel' => $strategyMap,
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

    public function insertTheme() {
        $condition = array_key_exists('Theme', filter_input_array(INPUT_POST)) && array_key_exists('StrategyMap', filter_input_array(INPUT_POST));
        if (!(count(filter_input_array(INPUT_POST)) > 0 && $condition)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $themeData = filter_input_array(INPUT_POST)['Theme'];
        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];

        $theme = new Theme();
        $theme->bindValuesUsingArray(array('theme' => $themeData), $theme);

        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData), $strategyMap);
        if ($theme->validate()) {
            try {
                $this->mapService->manageTheme($theme, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme);
                $_SESSION['notif'] = array('class' => 'success', 'message' => 'Theme added to Strategy Map. Please check the Strategy Map.');
            } catch (ServiceException $ex) {
                $_SESSION['validation'] = array($ex->getMessage());
            }
        } else {
            $_SESSION['validation'] = $theme->validationMessages;
        }
        $this->redirect(array('map/manageThemes', 'map' => $strategyMap->id));
    }

    public function listEnlistedThemes() {
        $themes = $this->mapService->listThemes();

        $data = array();
        foreach ($themes as $theme) {
            array_push($data, array('description' => $theme->description));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function updateTheme() {
        if (count(filter_input_array(INPUT_POST)) > 0 && array_key_exists('Theme', filter_input_array(INPUT_POST))) {
            $this->processThemeUpdate(filter_input_array(INPUT_POST));
        }

        $id = filter_input(INPUT_GET, 'id');
        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $theme = $this->loadThemeModel($id);
        $strategyMap = $this->loadStrategyMapModel(null, null, null, $theme);

        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Update Theme';
        $this->render('perspective/theme', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Themes' => array('map/manageThemes', 'map' => $strategyMap->id),
                'Update Theme' => 'active'),
            'themes' => $this->mapService->listThemes($strategyMap),
            'model' => $theme,
            'mapModel' => $strategyMap,
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    private function processThemeUpdate(array $themeData) {
        $theme = new Theme();
        $theme->bindValuesUsingArray(array('theme' => $themeData['Theme']), $theme);
        $oldTheme = clone $this->loadThemeModel($theme->id);
        $strategyMap = $this->loadStrategyMapModel(null, null, null, $theme);
        if ($theme->validate()) {
            if ($theme->computePropertyChanges($oldTheme) > 0) {
                $this->mapService->manageTheme($theme);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme, $oldTheme);
                $_SESSION['notif'] = array('class' => 'info', 'message' => 'Theme updated');
                $this->redirect(array('map/manageThemes', 'map' => $strategyMap->id));
            }
        } else {
            $_SESSION['validation'] = $theme->validationMessages;
            $this->redirect(array('map/updateTheme', 'id' => $theme->id));
        }
    }

    public function confirmDeleteTheme() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $theme = $this->loadThemeModel($id);
        $strategyMap = $this->loadStrategyMapModel(null, null, null, $theme);
        $this->title = ApplicationConstants::APP_NAME . ' - Delete Theme';
        $this->layout = "column-1";
        $this->render('commons/confirm', array(
            'confirm' => array('class' => 'error',
                'header' => 'Confirm Theme deletion',
                'text' => "Do you want to delete this theme? Continuing will remove the theme, <strong>{$theme->description}</strong>, in the Strategy Map",
                'accept.class' => 'red',
                'accept.text' => 'Yes',
                'accept.url' => array('map/deleteTheme', 'id' => $id),
                'deny.class' => 'green',
                'deny.text' => 'No',
                'deny.url' => array('map/manageThemes', 'map' => $strategyMap->id)),
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Themes' => array('map/manageThemes', 'map' => $strategyMap->id),
                'Delete Theme' => 'active')));
    }

    public function deleteTheme() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $theme = clone $this->loadThemeModel($id);
        $strategyMap = $this->loadStrategyMapModel(null, null, null, $theme);
        $this->mapService->deleteTheme($id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme);

        $_SESSION['notif'] = array('class' => '', 'message' => 'Theme deleted');
        $this->redirect(array('map/manageThemes', 'map' => $strategyMap->id));
    }

    public function manageObjectives() {
        $perspective = filter_input(INPUT_GET, 'perspective');
        $map = filter_input(INPUT_GET, 'map');

        if (isset($perspective) && !empty($perspective)) {
            $this->manageObjectivesByPerpective($perspective);
        } elseif (isset($map) && !empty($map)) {
            $strategyMap = $this->loadStrategyMapModel($map);
            $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
            $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
            $this->manageObjectivesByStrategyMap($strategyMap);
        } else {
            throw new ValidationException('Another parameter is needed to process this request');
        }
    }

    private function manageObjectivesByStrategyMap($strategyMap) {
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Objectives';
        $perspectives = ApplicationUtils::generateListData($this->mapService->listPerspectives($strategyMap), 'id', 'description');
        $themes = ApplicationUtils::generateListData($this->mapService->listThemes($strategyMap), 'id', 'description');
        $this->render('objective/form', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Objectives' => 'active'),
            'model' => new Objective(),
            'mapModel' => $strategyMap,
            'themeModel' => new Theme(),
            'perspectiveModel' => new Perspective(),
            'perspectives' => $perspectives,
            'themes' => $themes,
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }

        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    private function manageObjectivesByPerpective($perspective) {
        $perspectiveModel = $this->loadPerspectiveModel($perspective);
        $strategyMap = $this->loadStrategyMapModel(null, $perspectiveModel);

        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Objectives';
        $perspectives = ApplicationUtils::generateListData($this->mapService->listPerspectives($strategyMap), 'id', 'description');
        $themes = ApplicationUtils::generateListData($this->mapService->listThemes($strategyMap), 'id', 'description');
        $this->render('objective/form', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Objectives' => 'active'),
            'model' => new Objective(),
            'mapModel' => $strategyMap,
            'themeModel' => new Theme(),
            'perspectiveModel' => $perspectiveModel,
            'perspectives' => $perspectives,
            'themes' => $themes,
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }

        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function insertObjective() {
        if ((count(filter_input_array(INPUT_POST)) == 0) || (!$this->validatePostData(array('Perspective', 'Theme', 'Objective', 'StrategyMap')))) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $objectiveData = filter_input_array(INPUT_POST)['Objective'];
        $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];
        $themeData = filter_input_array(INPUT_POST)['Theme'];
        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];

        $strategyMap = $this->loadStrategyMapModel($strategyMapData['id']);

        $objective = new Objective();
        $objective->bindValuesUsingArray(array(
            'objective' => $objectiveData,
            'perspective' => $perspectiveData,
            'theme' => $themeData));

        if ($objective->validate()) {
            try {
                $this->mapService->addObjective($objective, $strategyMap);
                $objective->perspective = $this->loadPerspectiveModel($objective->perspective->id);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective);
                $_SESSION['notif'] = array('class' => 'success', 'message' => 'Objective added');
            } catch (ServiceException $ex) {
                $_SESSION['validation'] = array($ex->getMessage());
            }
        } else {
            $_SESSION['validation'] = $objective->validationMessages;
        }

        $referer = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        if (empty($referer)) {
            $this->redirect(array('site/index'));
        } else {
            $this->redirect($referer);
        }
    }

    public function updateObjective() {
        $id = filter_input(INPUT_GET, 'id');
        if ((count(filter_input_array(INPUT_POST)) > 0) && ($this->validatePostData(array('Perspective', 'Theme', 'Objective', 'StrategyMap')))) {
            $this->processObjectiveUpdate(filter_input_array(INPUT_POST));
        } elseif (isset($id) && !empty($id)) {
            $objective = $this->loadObjectiveModel($id);
            $strategyMap = $this->loadStrategyMapModel(NULL, NULL, $objective);

            $objective->startingPeriodDate = $objective->startingPeriodDate->format('Y-m-d');
            $objective->endingPeriodDate = $objective->endingPeriodDate->format('Y-m-d');
            $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
            $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');

            $this->layout = 'column-1';
            $this->title = ApplicationConstants::APP_NAME . ' - Update Objective';
            $perspectives = ApplicationUtils::generateListData($this->mapService->listPerspectives($strategyMap), 'id', 'description');
            $themes = ApplicationUtils::generateListData($this->mapService->listThemes($strategyMap), 'id', 'description');
            $this->render('objective/form', array(
                'breadcrumb' => array(
                    'Home' => array('site/index'),
                    'Strategy Map Directory' => array('map/index'),
                    'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                    'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                    'Manage Objectives' => array('map/manageObjectives', 'map' => $strategyMap->id),
                    'Update Objective' => 'active'),
                'model' => $objective,
                'mapModel' => $strategyMap,
                'themeModel' => $objective->theme,
                'perspectiveModel' => $objective->perspective,
                'perspectives' => $perspectives,
                'themes' => $themes,
                'notif' => $this->getSessionData('notif'),
                'validation' => $this->getSessionData('validation')
            ));
            $this->unsetSessionData('notif');
            $this->unsetSessionData('validation');
        } else {
            throw new ValidationException("Another parameter is needed to process this request");
        }
    }

    private function processObjectiveUpdate(array $data) {
        $themeId = $data['Theme']['id'];
        $perspectiveId = $data['Perspective']['id'];

        $perspective = $this->loadPerspectiveModel($perspectiveId);
        if (isset($themeId) && !empty($themeId)) {
            $theme = $this->loadThemeModel($themeId);
        }

        $objective = new Objective();
        $objective->bindValuesUsingArray(array('objective' => $data['Objective']));
        $objective->perspective = $perspective;
        $objective->theme = !isset($theme) ? null : $theme;

        $strategyMap = $this->loadStrategyMapModel(null, null, $objective);

        $oldObjective = clone $this->loadObjectiveModel($objective->id);

        if ($objective->validate()) {
            if ($objective->computePropertyChanges($oldObjective) > 0) {
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Objective Updated'));
                $this->mapService->updateObjective($objective);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective, $oldObjective);
            }
        } else {
            $this->setSessionData('validation', $objective->validationMessages);
        }
        $this->redirect(array('map/updateObjective', 'id' => $objective->id));
    }

    public function confirmDeleteObjective($id) {
        if (empty($id)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $objective = $this->loadObjectiveModel($id);
        $strategyMap = $this->loadStrategyMapModel(null, null, $objective);

        $this->layout = 'column-1';
        $this->render('commons/confirm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Complete Strategy Map' => array('map/complete', 'id' => $strategyMap->id),
                'Manage Objectives' => array('map/manageObjectives', 'map' => $strategyMap->id),
                'Confirm Delete Objective' => 'active'),
            'confirm' => array('class' => 'error',
                'header' => 'Confirm Objective deletion',
                'text' => "Do you want to delete this objective? Continuing will remove the objective, <strong>{$objective->description}</strong>, in the Strategy Map",
                'accept.class' => 'red',
                'accept.text' => 'Yes',
                'accept.url' => array('map/deleteObjective', 'id' => $id),
                'deny.class' => 'green',
                'deny.text' => 'No',
                'deny.url' => array('map/manageObjectives', 'map' => $strategyMap->id))
        ));
    }

    public function deleteObjective($id) {
        if (empty($id)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $objective = clone $this->loadObjectiveModel($id);
        $strategyMap = $this->loadStrategyMapModel(null, null, $objective);

        $this->mapService->deleteObjective($id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective);

        $this->setSessionData('notif', array('class' => '', 'message' => 'Objective deleted'));
        $this->redirect(array('map/manageObjectives', 'map' => $strategyMap->id));
    }

    public function validateObjective() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            $this->renderAjaxJsonResponse(array('respCode' => '50'));
            return;
        } elseif (!$this->validatePostData(array('Perspective', 'Theme', 'Objective', 'mode'))) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            return;
        }

        $objectiveData = filter_input_array(INPUT_POST)['Objective'];
        $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];
        $themeData = filter_input_array(INPUT_POST)['Theme'];
        $mode = filter_input_array(INPUT_POST)['mode'];

        $objective = new Objective();
        $objective->bindValuesUsingArray(array(
            'objective' => $objectiveData,
            'perspective' => $perspectiveData,
            'theme' => $themeData));
        $objective->validationMode = $mode;

        $this->remoteValidateModel($objective);
    }

    public function renderObjectivesTable() {
        $map = filter_input(INPUT_POST, 'map');

        if (!isset($map) || empty($map)) {
            throw new ValidationException("Another parameter is needed to process this request");
        }

        $strategyMap = $this->mapService->getStrategyMap($map);

        if (is_null($strategyMap->id)) {
            throw new ValidationException("Strategy Map not found.");
        }

        $objectives = $this->mapService->listObjectives($strategyMap);
        $data = array();
        foreach ($objectives as $objective) {
            array_push($data, array('id' => $objective->id,
                'description' => $objective->description,
                'perspective' => $objective->perspective->positionOrder . ' - ' . $objective->perspective->description,
                'theme' => $objective->theme->description,
                'actions' => ApplicationUtils::generateLink(array('map/updateObjective', 'id' => $objective->id), 'Update') . '&nbsp;|&nbsp;' . ApplicationUtils::generateLink(array('map/confirmDeleteObjective', 'id' => $objective->id), 'Delete')));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function loadStrategyMapModel($id = null, Perspective $perspective = null, Objective $objective = null, Theme $theme = null) {
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

    private function loadThemeModel($id) {
        $theme = $this->mapService->getTheme($id);

        if (is_null($theme->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Theme not found');
            $this->redirect(array('map/index'));
        } else {
            return $theme;
        }
    }

    private function loadObjectiveModel($id) {
        $objective = $this->mapService->getObjective($id);

        if (is_null($objective->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Objective not found');
            $this->redirect(array('map/index'));
        } else {
            return $objective;
        }
    }

}
