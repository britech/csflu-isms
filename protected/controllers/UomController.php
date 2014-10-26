<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\service\commons\UnitOfMeasureSimpleImpl as UnitOfMeasureService;
use org\csflu\isms\util\ApplicationUtils;

/**
 * Description of UomController
 *
 * @author britech
 */
class UomController extends Controller {

    private $uomService;

    public function __construct() {
        $this->layout = 'column-2';
        $this->uomService = new UnitOfMeasureService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Unit of Measures';

        $this->render('uom/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Manage Unit of Measures' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header'=>'Actions',
                    'links'=>array(
                        'Add UOM' => array('uom/createUom')
                    )))
        ));
    }

    public function listUnitofMeasures() {
        $uoms = $this->uomService->listUnitOfMeasures();
        $data = array();

        foreach ($uoms as $uom) {
            array_push($data, array(
                'symbol' => $uom->symbol,
                'name' => $uom->description,
                'action' => ApplicationUtils::generateLink(array('uom/updateUom', 'id' => $uom->id), 'Update Data')));
        }
        $this->renderAjaxJsonResponse($data);
    }

}
