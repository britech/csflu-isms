<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\controllers\support\ModelLoaderUtil;

/**
 * Description of ActivityController
 *
 * @author britech
 */
class ActivityController extends Controller {

    private $modelLoaderUtil;

    public function __construct() {
        $this->checkAuthorization();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
    }

    public function index($initiative, $period) {
        $date = \DateTime::createFromFormat('Y-m-d', "{$period}-1");
        $data = $this->loadInitiativeModel($initiative);

        $this->title = ApplicationConstants::APP_NAME . ' - Activity Dashboard';
        $this->layout = 'column-2';
        $this->render('activity/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => 'active'
            ),
            'sidebar' => array(
                'file' => 'activity/_index-navi'
            ),
            'data' => $data,
            'date' => $date,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function manage($id, $period) {
        $activity = $this->loadModel($id);
        $initiative = $this->loadInitiativeModel(null, $activity);

        $this->title = ApplicationConstants::APP_NAME;
        $this->layout = "column-2";
        $this->render('activity/manage', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Initiatives' => array('initiative/manage'),
                'Activity Dashboard' => array('activity/index', 'initiative' => $initiative->id, 'period' => $period),
                'Manage Activity' => 'active'
            ),
            'sidebar' => array(
                'file' => 'activity/_manage-navi'
            ),
            'data' => $activity,
            'initiative' => $initiative,
            'period' => $period
        ));
    }

    public function updateStatus() {
        $this->validatePostData(array('id', 'status', 'period'), false);

        $id = $this->getFormData('id');
        $status = strtoupper($this->getFormData('status'));
        $period = $this->getFormData('period');

        $activity = $this->loadModel($id, true);
        $initiative = $this->loadInitiativeModel(null, $activity, true);
        $this->setSessionData('notif', array('class' => 'info', 'message' => "{$activity->title} set to {$activity->translateStatusCode($status)}"));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('activity/index', 'initiative' => $initiative->id, 'period' => $period))));
    }

    private function loadInitiativeModel($id = null, Activity $activity = null, $remote = false) {
        if (!is_null($id)) {
            $initiative = $this->modelLoaderUtil->loadInitiativeModel($id, null, array(ModelLoaderUtil::KEY_REMOTE => $remote));
        } elseif (!is_null($activity)) {
            $component = $this->modelLoaderUtil->loadComponentModel(null, $activity, array(ModelLoaderUtil::KEY_REMOTE => $remote));
            $phase = $this->modelLoaderUtil->loadPhaseModel(null, $component, array(ModelLoaderUtil::KEY_REMOTE => $remote));
            $initiative = $this->modelLoaderUtil->loadInitiativeModel(null, $phase, array(ModelLoaderUtil::KEY_REMOTE => $remote));
        } else {
            throw new ControllerException("No arguments defined");
        }
        $initiative->startingPeriod = $initiative->startingPeriod->format('Y-m-d');
        $initiative->endingPeriod = $initiative->endingPeriod->format('Y-m-d');
        return $initiative;
    }
    
    private function loadModel($id, $remote = false) {
        return $this->modelLoaderUtil->loadActivityModel($id, array(ModelLoaderUtil::KEY_REMOTE => $remote));
    }

}
