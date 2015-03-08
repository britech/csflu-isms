<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\Model;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\WigSession;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\models\uam\ModuleAction;
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
            'model' => new WigSession(),
            'ubtModel' => $unitBreakthrough,
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function listMeetings() {
        $this->validatePostData(array('ubt'));

        $id = $this->getFormData('ubt');
        $unitBreakthrough = $this->loadUbtModel($id);
        $data = array();
        $pointer = 0;
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

    public function insert() {
        $this->validatePostData(array('WigSession', 'UnitBreakthrough'));

        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');
        $wigMeetingData = $this->getFormData('WigSession');

        $unitBreakthrough = $this->loadUbtModel($unitBreakthroughData['id']);
        $wigSession = new WigSession();
        $wigSession->bindValuesUsingArray(array('wigsession' => $wigMeetingData), $wigSession);

        if (!$wigSession->validate()) {
            $this->setSessionData('validation', $wigSession->validationMessages);
            $this->redirect(array('wig/index', 'ubt' => $unitBreakthrough->id));
        }

        try {
            $id = $this->ubtService->insertWigSession($wigSession, $unitBreakthrough);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $wigSession);
            $this->redirect(array('wig/view', 'id' => $id));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('wig/index', 'ubt' => $unitBreakthrough->id));
        }
    }

    private function resolveActionLinks(WigSession $wigMeeting) {
        $link = array(ApplicationUtils::generateLink(array('wig/view', 'id' => $wigMeeting->id), 'View'));
        if ($wigMeeting->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN && count($wigMeeting->commitments) == 0 && is_null($wigMeeting->movementUpdate)) {
            array_push($link, ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$wigMeeting->id}")));
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
