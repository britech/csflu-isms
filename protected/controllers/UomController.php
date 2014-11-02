<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\service\commons\UnitOfMeasureSimpleImpl as UnitOfMeasureService;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\exceptions\ValidationException;

/**
 * Description of UomController
 *
 * @author britech
 */
class UomController extends Controller {

    private $uomService;

    public function __construct() {
        $this->layout = 'column-1';
        $this->uomService = new UnitOfMeasureService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Unit of Measures';
        $this->layout = 'column-2';
        $this->render('uom/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit of Measures' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Add UOM' => array('uom/createUom')
                    ))),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function listUnitofMeasures() {
        $uoms = $this->uomService->listUnitOfMeasures();
        $data = array();

        foreach ($uoms as $uom) {
            array_push($data, array(
                'id'=>$uom->id,
                'symbol' => $uom->symbol,
                'name' => $uom->description,
                'action' => ApplicationUtils::generateLink(array('uom/updateUom', 'id' => $uom->id), 'Update Data')));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function createUom() {
        $this->title = ApplicationConstants::APP_NAME . ' - Add UOM';
        $this->render('uom/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit of Measures' => array('uom/index'),
                'Add UOM' => 'active'
            ),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));
        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function create() {
        $uomData = filter_input_array(INPUT_POST)['UnitOfMeasure'];

        $uom = new UnitOfMeasure;
        $uom->bindValuesUsingArray(array('unitofmeasure' => $uomData), $uom);

        if ($uom->validate()) {
            $this->uomService->manageUnitOfMeasures($uom);
            $_SESSION['notif'] = array('class' => 'success', 'message' => 'UOM successfully added');
            $this->redirect(array('uom/index'));
        } else {
            $_SESSION['validation'] = $uom->validationMessages;
            $this->redirect(array('uom/createUom'));
        }
    }

    public function updateUom() {
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $uom = $this->uomService->getUomInfo($id);

        if (is_null($uom->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'No data found');
            $this->redirect(array('uom/index'));
        }
        $this->title = ApplicationConstants::APP_NAME . ' - Update UOM';

        $this->render('uom/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit of Measures' => array('uom/index'),
                'Update UOM' => 'active'
            ),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : "",
            'data' => $uom
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function update() {
        $uomData = filter_input_array(INPUT_POST)['UnitOfMeasure'];

        $uom = new UnitOfMeasure;
        $uom->bindValuesUsingArray(array('unitofmeasure' => $uomData), $uom);

        if ($uom->validate()) {
            $this->uomService->manageUnitOfMeasures($uom);
            $_SESSION['notif'] = array('class' => 'info', 'message' => 'UOM successfully updated');
            $this->redirect(array('uom/index'));
        } else {
            $_SESSION['validation'] = $uom->validationMessages;
            $this->redirect(array('uom/updateUom', 'id'=>$uom->id));
        }
    }

}
