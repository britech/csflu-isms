<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\commons\RevisionHistoryLoggingServiceImpl;

/**
 * Description of RevisionController
 *
 * @author britech
 */
class RevisionController extends Controller {

    private $modelLoaderUtil;
    private $loggingService;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = "column-2";
        $this->isRbacEnabled = true;
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->loggingService = new RevisionHistoryLoggingServiceImpl();
    }

    public function strategyMap($id) {
        $this->moduleCode = ModuleAction::MODULE_SMAP;
        $this->actionCode = "SMAPM";

        $strategyMap = $this->modelLoaderUtil->loadMapModel($id);
        $this->render('revision/index', array(
            self::COMPONENT_BREADCRUMB => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Revision History' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'revision/_navi'
            ),
            'id' => $strategyMap->id,
            'module' => $this->moduleCode
        ));
    }

    public function measureProfile($id) {
        $this->moduleCode = ModuleAction::MODULE_SCARD;
        $this->actionCode = "MPM";

        $measureProfile = $this->modelLoaderUtil->loadMeasureProfileModel($id);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, $measureProfile->objective);
        $this->render('revision/index', array(
            self::COMPONENT_BREADCRUMB => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profile' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Revision History' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'revision/_navi'
            ),
            'id' => $measureProfile->id,
            'module' => $this->moduleCode
        ));
    }

    public function retrieveList() {
        $this->validatePostData(array('module', 'id'));

        $moduleCode = $this->getFormData('module');
        $referenceId = $this->getFormData('id');
        $revisions = $this->loggingService->getRevisionHistoryList($moduleCode, $referenceId);
        $data = array();
        foreach ($revisions as $revision) {
            array_push($data, array(
                'logStamp' => $revision->revisionTimestamp->format('M d, Y H:i:s A'),
                'user' => $revision->employee->getFullName(),
                'action' => $revision->translateRevisionType(),
                'notes' => $revision->resolveNotes()
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

}
