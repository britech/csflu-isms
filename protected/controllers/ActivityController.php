<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
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

    public function index($initiative, $startingPeriod) {
        $date = \DateTime::createFromFormat('Y-m-d', "{$startingPeriod}-1");
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
            'date' => $date
        ));
    }

    private function loadInitiativeModel($id = null, Phase $phase = null, $remote = false) {
        $initiative = $this->modelLoaderUtil->loadInitiativeModel($id, $phase, array(ModelLoaderUtil::KEY_REMOTE => $remote));
        $initiative->startingPeriod = $initiative->startingPeriod->format('Y-m-d');
        $initiative->endingPeriod = $initiative->endingPeriod->format('Y-m-d');
        return $initiative;
    }

}
