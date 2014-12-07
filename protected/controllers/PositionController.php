<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\service\commons\PositionServiceSimpleImpl as PositionService;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\commons\Position;
use org\csflu\isms\exceptions\ControllerException;

/**
 * Description of PositionController
 *
 * @author britech
 */
class PositionController extends Controller {

    private $positionService;

    public function __construct() {
        $this->checkAuthorization();
        $this->positionService = new PositionService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Positions';
        $this->render('position/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Positions' => 'active'
            ),
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

    public function renderPositionGrid() {
        $positions = $this->positionService->listPositions();
        $data = array();
        foreach ($positions as $position) {
            array_push($data, array(
                'name' => $position->name,
                'action' => ApplicationUtils::generateLink(array('position/updatePosition', 'id' => $position->id), 'Update Position')
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create() {
        $positionData = filter_input_array(INPUT_POST)['Position'];

        $position = new Position();
        $position->bindValuesUsingArray(array('position' => $positionData), $position);

        if ($position->validate()) {
            $this->positionService->managePosition($position);
            $_SESSION['notif'] = array('class' => 'success', 'message' => 'Position successfully added');
        } else {
            $_SESSION['validation'] = $position->validationMessages;
        }
        $this->redirect(array('position/index'));
    }

    public function updatePosition() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) && empty($id)) {
            throw new ControllerException('Another parameter is needed process this request');
        }
        $position = $this->positionService->getPositionData($id);

        if (is_null($position->id)) {
            $_SESSION['notif'] = array('class' => 'warning', 'message' => 'Selected position does not exist');
            $this->redirect(array('position/index'));
        } else {
            $this->title = ApplicationConstants::APP_NAME . ' - Update Position';
            $this->render('position/index', array(
                'breadcrumb' => array(
                    'Home' => array('site/index'),
                    'Manage Positions' => array('position/index'),
                    'Update Position' => 'active'
                ),
                'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
                'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : "",
                'data' => $position
            ));
            if (isset($_SESSION['validation'])) {
                unset($_SESSION['validation']);
            }
            if (isset($_SESSION['notif'])) {
                unset($_SESSION['notif']);
            }
        }
    }

    public function update() {
        $positionData = filter_input_array(INPUT_POST)['Position'];

        $position = new Position();
        $position->bindValuesUsingArray(array('position' => $positionData), $position);

        if ($position->validate()) {
            $this->positionService->managePosition($position);
            $_SESSION['notif'] = array('class' => 'info', 'message' => 'Position successfully updated');
        } else {
            $_SESSION['validation'] = $position->validationMessages;
        }
        $this->redirect(array('position/index'));
    }

    public function listPositions() {
        $positions = $this->positionService->listPositions();
        $data = array();
        foreach ($positions as $position) {
            array_push($data, array(
                'id' => $position->id,
                'name' => '&nbsp;'.$position->name
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

}
