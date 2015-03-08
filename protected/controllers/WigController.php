<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\WigMeeting;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl as UnitBreakthroughManagementService;

/**
 * Description of WigController
 *
 * @author britech
 */
class WigController extends Controller {

    private $ubtService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->ubtService = new UnitBreakthroughManagementService();
    }

    public function index($ubt) {
        $unitBreakthrough = $this->loadUbtModel($ubt);

        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');

        $this->render('ubt/manage-wig', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit Breakthroughs' => array('ubt/manage'),
                'Manage WIG Sessions' => 'active'
            ),
            'model' => new WigMeeting(),
            'ubtModel' => $unitBreakthrough
        ));
    }

    public function listMeetings() {
        $this->validatePostData(array('ubt'));

        $id = $this->getFormData('ubt');
        $unitBreakthrough = $this->loadUbtModel($id);
        $data = array();
        $pointer = 1;
        foreach ($unitBreakthrough->wigMeetings as $wigMeeting) {
            array_push($data, array(
                'number' => $pointer,
                'timeline' => "{$wigMeeting->startingPeriod->format('M. j')} - {$wigMeeting->endingPeriod->format('M. j')}",
                'status' => $wigMeeting->translateWigMeetingEnvironmentStatus(),
                'action' => $this->resolveActionLinks($wigMeeting)
            ));
            $pointer++;
        }

        $this->renderAjaxJsonResponse($data);
    }

    private function resolveActionLinks(WigMeeting $wigMeeting) {
        $link = array();

        if ($wigMeeting->wigMeetingEnvironmentStatus == WigMeeting::STATUS_OPEN) {
            if (count($wigMeeting->commitments) && is_null($wigMeeting->movementUpdate)) {
                array_push($link, ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$wigMeeting->id}")));
            }
        } else {
            
        }
        return implode('&nbsp;|&nbsp;', $link);
    }

    private function loadUbtModel($id, $remote = false) {
        $unitBreakthrough = $this->ubtService->getUnitBreakthrough($id);
        if (is_null($unitBreakthrough->id)) {
            $this->setSessionData('notif', array('message' => 'Unit Breakthrough not found'));
            $url = array('map/index');
            if ($remote) {
                $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl($url)));
            } else {
                $this->redirect($url);
            }
        }
        $unitBreakthrough->validationMode = Model::VALIDATION_MODE_UPDATE;
        return $unitBreakthrough;
    }

}
