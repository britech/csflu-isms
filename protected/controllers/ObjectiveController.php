<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\map\Theme;
use org\csflu\isms\models\map\Perspective;
use org\csflu\isms\models\map\StrategyMap;

/**
 * Description of ObjectiveController
 *
 * @author britech
 */
class ObjectiveController extends Controller {

    private $mapService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapService();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->layout = 'column-1';
    }

    public function manage($map) {
        if (!isset($map) || empty($map)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $strategyMap = $this->loadMapModel($map);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Objectives';
        $this->renderView($strategyMap);
    }

    public function validate() {
        try {
            $this->validatePostData(array('Perspective', 'Theme', 'Objective', 'mode'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
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

    public function insert() {
        $this->validatePostData(array('Perspective', 'Theme', 'Objective', 'StrategyMap'));
        $objectiveData = $this->getFormData('Objective');
        $perspectiveData = $this->getFormData('Perspective');
        $themeData = $this->getFormData('Theme');
        $strategyMapData = $this->getFormData('StrategyMap');

        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        $objective = new Objective();
        $objective->bindValuesUsingArray(array('objective' => $objectiveData, 'perspective' => $perspectiveData, 'theme' => $themeData));

        if ($objective->validate()) {
            try {
                $this->mapService->addObjective($objective, $strategyMap);
                $objective->perspective = $this->loadPerspectiveModel($objective->perspective->id);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Objective added'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', $objective->validationMessages);
        }
        $this->redirect(array('objective/manage', 'map' => $strategyMap->id));
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('Perspective', 'Theme', 'Objective', 'StrategyMap'));
            $this->processUpdate();
        } elseif (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $objective = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(NULL, $objective);

        $this->title = ApplicationConstants::APP_NAME . ' - Update Objective';
        $this->renderView($strategyMap, $objective);
    }

    private function renderView(StrategyMap $strategyMap, Objective $objective = null) {
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        if (!is_null($objective)) {
            $objective->startingPeriodDate = $objective->startingPeriodDate->format('Y-m-d');
            $objective->endingPeriodDate = $objective->endingPeriodDate->format('Y-m-d');
        }
        $perspectives = ApplicationUtils::generateListData($this->mapService->listPerspectives($strategyMap), 'id', 'description');
        $themes = ApplicationUtils::generateListData($this->mapService->listThemes($strategyMap), 'id', 'description');
        $this->render('objective/form', array(
            'breadcrumb' => $this->resolveBreadcrumbs($strategyMap, $objective),
            'model' => is_null($objective) ? new Objective() : $objective,
            'mapModel' => $strategyMap,
            'themeModel' => is_null($objective) ? new Theme : $objective->theme,
            'perspectiveModel' => is_null($objective) ? new Perspective : $objective->perspective,
            'perspectives' => $perspectives,
            'themes' => $themes,
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    private function resolveBreadcrumbs(StrategyMap $strategyMap, Objective $objective = null) {
        return is_null($objective) ? $this->getInitialBreadcrumbs($strategyMap) : $this->getUpdateBreadcrumbs($strategyMap);
    }

    private function getInitialBreadcrumbs(StrategyMap $strategyMap) {
        return array('Home' => array('site/index'),
            'Strategy Map Directory' => array('map/index'),
            'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
            'Manage Objectives' => 'active');
    }

    private function getUpdateBreadcrumbs(StrategyMap $strategyMap) {
        return array('Home' => array('site/index'),
            'Strategy Map Directory' => array('map/index'),
            'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
            'Manage Objectives' => array('objective/manage', 'map' => $strategyMap->id),
            'Update Objective' => 'active');
    }

    private function processUpdate() {
        $themeId = $this->getFormData('Theme')['id'];
        $perspectiveId = $this->getFormData('Perspective')['id'];
        $objectiveData = $this->getFormData('Objective');

        $perspective = $this->loadPerspectiveModel($perspectiveId);
        if (isset($themeId) && !empty($themeId)) {
            $theme = $this->loadThemeModel($themeId);
        }

        $objective = new Objective();
        $objective->bindValuesUsingArray(array('objective' => $objectiveData));
        $objective->perspective = $perspective;
        $objective->theme = !isset($theme) ? null : $theme;

        $strategyMap = $this->loadMapModel(null, $objective);
        $oldObjective = clone $this->loadModel($objective->id);

        if ($objective->validate()) {
            if ($objective->computePropertyChanges($oldObjective) > 0) {
                $this->updateObjective($objective, $strategyMap, $oldObjective);
            }
            $this->redirect(array('objective/manage', 'map' => $strategyMap->id));
        } else {
            $this->setSessionData('validation', $objective->validationMessages);
            $this->redirect(array('objective/update', 'id' => $objective->id));
        }
    }

    private function updateObjective(Objective $objective, StrategyMap $strategyMap, Objective $oldObjective) {
        try {
            $this->mapService->updateObjective($objective);
            $this->setSessionData('notif', array('class' => 'info', 'message' => 'Objective Updated'));
            $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective, $oldObjective);
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('objective/update', 'id' => $objective->id));
        }
    }

    public function delete() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $objective = clone $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $objective);

        $this->mapService->deleteObjective($id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SMAP, $strategyMap->id, $objective);
        $this->setSessionData('notif', array('class' => '', 'message' => 'Objective deleted'));

        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('objective/manage', 'map' => $strategyMap->id))));
    }

    public function renderTable() {
        $this->validatePostData(array('map'));
        $map = $this->getFormData('map');

        $strategyMap = $this->mapService->getStrategyMap($map);

        if (is_null($strategyMap->id)) {
            throw new ControllerException("Strategy Map not found");
        }

        $objectives = $this->mapService->listObjectives($strategyMap);
        $data = array();
        foreach ($objectives as $objective) {
            array_push($data, array('id' => $objective->id,
                'description' => $objective->description,
                'perspective' => $objective->perspective->positionOrder . ' - ' . $objective->perspective->description,
                'theme' => is_null($objective->theme->description) ? "--" : $objective->theme->description,
                'actions' => ApplicationUtils::generateLink(array('objective/update', 'id' => $objective->id), 'Update') . '&nbsp;|&nbsp;' . ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$objective->id}"))));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function listObjectives() {
        $this->validatePostData(array('map'));
        $map = $this->getFormData('map');

        $strategyMap = $this->mapService->getStrategyMap($map);

        if (is_null($strategyMap->id)) {
            throw new ControllerException("Strategy Map not found");
        }

        $objectives = $this->mapService->listObjectives($strategyMap);
        $data = array();
        foreach ($objectives as $objective) {
            array_push($data, array(
                'id' => $objective->id,
                'objective' => $objective->description,
                'description' => "{$objective->description}&nbsp;({$objective->perspective->description})"
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function listAll() {
        $objectives = $this->mapService->listObjectives();
        $data = array();
        foreach ($objectives as $objective) {
            array_push($data, array('description' => $objective->description));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function get() {
        try {
            $this->validatePostData(array('id'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }
        $id = $this->getFormData('id');
        $objective = $this->mapService->getObjective($id);
        $this->renderAjaxJsonResponse(array(
            'id' => $objective->id,
            'startingPeriodDate' => $objective->startingPeriodDate->format('Y-m-d'),
            'endingPeriodDate' => $objective->endingPeriodDate->format('Y-m-d')
        ));
    }

    private function loadModel($id) {
        $objective = $this->mapService->getObjective($id);

        if (is_null($objective->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Objective not found'));
            $this->redirect(array('map/index'));
        } else {
            return $objective;
        }
    }

    private function loadMapModel($id = null, Objective $objective = null, Perspective $perspective = null) {
        $strategyMap = $this->mapService->getStrategyMap($id, $perspective, $objective, null);

        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

    private function loadPerspectiveModel($id) {
        $perspective = $this->mapService->getPerspective($id);

        if (is_null($perspective->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Perspective not found'));
            $this->redirect(array('map/index'));
        } else {
            return $perspective;
        }
    }

    private function loadThemeModel($id) {
        $theme = $this->mapService->getTheme($id);

        if (is_null($theme->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Theme not found'));
            $this->redirect(array('map/index'));
        } else {
            return $theme;
        }
    }

}
