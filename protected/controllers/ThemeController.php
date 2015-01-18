<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\map\Theme;

/**
 * Description of ThemeController
 *
 * @author britech
 */
class ThemeController extends Controller {

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
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $strategyMap = $this->loadMapModel($map);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Themes';
        $this->render('perspective/theme', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Themes' => 'active'),
            'themes' => $this->mapService->listThemes($strategyMap),
            'model' => new Theme(),
            'mapModel' => $strategyMap,
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif'),
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function insert() {
        $this->validatePostData(array('Theme', 'StrategyMap'));
        $themeData = $this->getFormData('Theme');
        $strategyMapData = $this->getFormData('StrategyMap');

        $theme = new Theme();
        $theme->bindValuesUsingArray(array('theme' => $themeData), $theme);

        $strategyMap = $this->loadMapModel($strategyMapData['id']);
        if ($theme->validate()) {
            try {
                $this->mapService->manageTheme($theme, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Theme added to Strategy Map. Please check the Strategy Map.'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', $theme->validationMessages);
        }
        $this->redirect(array('theme/manage', 'map' => $strategyMap->id));
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('Theme'));
            $this->processUpdate();
        } elseif (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $theme = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $theme);

        $this->title = ApplicationConstants::APP_NAME . ' - Update Theme';
        $this->render('perspective/theme', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Themes' => array('map/manageThemes', 'map' => $strategyMap->id),
                'Update Theme' => 'active'),
            'themes' => $this->mapService->listThemes($strategyMap),
            'model' => $theme,
            'mapModel' => $strategyMap,
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function delete() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $theme = clone $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $theme);
        $this->mapService->deleteTheme($id);

        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme);
        $this->setSessionData('notif', array('class'=>'', 'message'=>'Theme deleted in the Strategy Map'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('theme/manage', 'map' => $strategyMap->id))));
    }

    public function listThemes() {
        $themes = $this->mapService->listThemes();

        $data = array();
        foreach ($themes as $theme) {
            array_push($data, array('description' => $theme->description));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function processUpdate() {
        $theme = new Theme();
        $theme->bindValuesUsingArray(array('theme' => $this->getFormData('Theme')), $theme);

        $oldTheme = clone $this->loadModel($theme->id);
        $strategyMap = $this->loadMapModel(null, $oldTheme);
        if ($theme->validate()) {
            if ($theme->computePropertyChanges($oldTheme) > 0) {
                $this->mapService->manageTheme($theme);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $theme, $oldTheme);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Theme updated'));
            }
            $this->redirect(array('theme/manage', 'map' => $strategyMap->id));
        } else {
            $this->setSessionData('validation', $theme->validationMessages);
            $this->redirect(array('theme/update', 'id' => $theme->id));
        }
    }

    private function loadModel($id) {
        $theme = $this->mapService->getTheme($id);
        if (is_null($theme->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Theme not found'));
            $this->redirect(array('map/index'));
        } else {
            return $theme;
        }
    }

    private function loadMapModel($id = null, Theme $theme = null) {
        $strategyMap = $this->mapService->getStrategyMap($id, null, null, $theme);

        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

}
