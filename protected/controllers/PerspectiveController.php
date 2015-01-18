<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\core\Model;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\map\Perspective;

/**
 *
 *
 * @author britech
 */
class PerspectiveController extends Controller {

    private $mapService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->mapService = new StrategyMapService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function manage($map) {
        if (!isset($map) || empty($map)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $this->title = ApplicationConstants::APP_NAME . ' - Add Perspective';
        $strategyMap = $this->loadMapModel($map, null);
        $this->layout = 'column-1';
        $this->render('perspective/insert', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $map),
                'Manage Perspectives' => 'active'),
            'id' => $strategyMap->id,
            'perspectiveList' => $this->mapService->listPerspectives($strategyMap),
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif'),
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('Perspective', 'StrategyMap'));
        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $perspectiveData = filter_input_array(INPUT_POST)['Perspective'];

        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);

        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData), $strategyMap);

        $perspective->validationMode = Model::VALIDATION_MODE_INITIAL;
        if ($perspective->validate()) {
            try {
                $this->mapService->insertPerspective($perspective, $strategyMap);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SMAP, $strategyMap->id, $perspective);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Perspective added. Please check the Strategy Map now.'));
            } catch (ServiceException $ex) {
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', $perspective->validationMessages);
        }
        $this->redirect(array('perspective/manage', 'map' => $strategyMap->id));
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('Perspective'));
            $this->processUpdate();
        } elseif (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $perspective = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $perspective);

        $this->title = ApplicationConstants::APP_NAME . ' - Update Perspective';
        $this->layout = 'column-1';
        $this->render('perspective/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Perspectives' => array('perspective/manage', 'map' => $strategyMap->id),
                'Update Perspective' => 'active'),
            'perspective' => $perspective,
            'validation' => $this->getSessionData('validation'),
        ));
        $this->unsetSessionData('validation');
    }

    private function processUpdate() {
        $perspectiveData = $this->getFormData('Perspective');
        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);
        $perspective->validationMode = Model::VALIDATION_MODE_UPDATE;

        if ($perspective->validate()) {
            $this->updatePerspective($perspective);
        } else {
            $this->setSessionData('validation', $perspective->validationMessages);
            $this->redirect(array('map/updatePerspective', 'id' => $perspective->id));
        }
    }

    private function updatePerspective(Perspective $perspective) {
        $strategyMap = $this->loadMapModel(null, $perspective);
        $oldPerspective = clone $this->loadModel($perspective->id);
        try {
            if ($perspective->computePropertyChanges($oldPerspective) > 0) {
                $this->mapService->updatePerspective($perspective);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SMAP, $strategyMap->id, $perspective, $oldPerspective);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Perspective updated'));
            }
            $this->redirect(array('perspective/manage', 'map' => $strategyMap->id));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('map/updatePerspective', 'id' => $perspective->id));
        }
    }
    
    public function delete() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');
        
        $perspective = clone $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $perspective);
        $this->mapService->deletePerspective($id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SMAP, $strategyMap->id, $perspective);
        $this->setSessionData('notif', array('class' => '', 'message' => 'Perspective removed from the Strategy Map'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('perspective/manage', 'map' => $strategyMap->id))));
    }

    private function loadModel($id) {
        $perspective = $this->mapService->getPerspective($id);

        if (is_null($perspective->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Perspective not found'));
            $this->redirect(array('map/index'));
        } else {
            return $perspective;
        }
    }

    private function loadMapModel($id = null, Perspective $perspective = null) {
        $strategyMap = $this->mapService->getStrategyMap($id, $perspective);

        if (is_null($strategyMap->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        } else {
            return $strategyMap;
        }
    }

    public function validate() {
        try{
            $this->validatePostData(array('Perspective', 'mode'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode'=>'70'));
        }

        $perspectiveData = $this->getFormData('Perspective');
        $mode = $this->getFormData('mode');

        $perspective = new Perspective();
        $perspective->bindValuesUsingArray(array('perspective' => $perspectiveData), $perspective);
        $perspective->validationMode = $mode;

        $this->remoteValidateModel($perspective);
    }

    public function listPerspectives() {
        $perspectives = $this->mapService->listPerspectives();

        $perspectiveArray = array();

        foreach ($perspectives as $perspective) {
            array_push($perspectiveArray, array('description' => $perspective->description));
        }
        $this->renderAjaxJsonResponse($perspectiveArray);
    }
}
