<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ValidationException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl as IndicatorManagementService;
use org\csflu\isms\models\indicator\Indicator;

class KmController extends Controller {

    private $indicatorService;

    public function __construct() {
        $this->checkAuthorization();
        $this->indicatorService = new IndicatorManagementService();
        $this->layout = 'column-2';
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Knowledge Management';
        $this->render('km/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Knowledge Management',
                    'links' => array(
                        'Indicators' => array('km/indicators'),
                        'Generate Reports' => array('km/reportsList')
                    )
                ))
        ));
    }

    public function indicators() {
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Indicators';
        $this->render('indicator/indicatorList', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Enlist an Indicator' => array('km/enlistIndicator'),
                        'Link Indicator to a Job Position' => array('km/linkIndicator')
                    ))),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function renderIndicatorGrid() {
        $indicators = $this->indicatorService->listIndicators();

        $data = array();
        foreach ($indicators as $indicator) {
            array_push($data, array(
                'description' => $indicator->description,
                'action' => ApplicationUtils::generateLink(array('km/indicatorProfile', 'id' => $indicator->id), 'View Indicator') . '&nbsp;|&nbsp;'
                . ApplicationUtils::generateLink(array('km/updateIndicator', 'id' => $indicator->id), 'Update Indicator') . '&nbsp;|&nbsp;'
                . ApplicationUtils::generateLink(array('km/manageBaselineData', 'indicator' => $indicator->id), 'Update Baseline')
            ));
        }

        $this->renderAjaxJsonResponse($data);
    }

    public function enlistIndicator() {
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist An Indicator';
        $this->layout = 'column-1';
        $this->render('indicator/mainForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Enlist An Indicator' => 'active'),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));

        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function create() {
        $indicatorData = filter_input_array(INPUT_POST)['Indicator'];
        $uomData = filter_input_array(INPUT_POST)['UnitOfMeasure'];

        if (count(filter_input_array(INPUT_POST)) < 2) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicator = new Indicator();
        $indicator->validationMode = Model::VALIDATION_MODE_INITIAL;
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'unitofmeasure' => $uomData
        ));

        if ($indicator->validate()) {
            $id = $this->indicatorService->enlistIndicator($indicator);
            $_SESSION['notif'] = array('class' => 'success', 'message' => 'Indicator successfully added');
            $this->redirect(array('km/updateIndicator', 'id' => $id));
        } else {
            $_SESSION['validation'] = $indicator->validationMessages;
            $this->redirect(array('km/enlistIndicator'));
        }
    }

    public function indicatorProfile() {
        $this->title = ApplicationConstants::APP_NAME . ' - Indicator Profile';
        //$this->layout = 'column-1';
        $id = filter_input(INPUT_GET, 'id');

        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicator = $this->indicatorService->retrieveIndicator($id);

        if (is_null($indicator->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        }

        $this->render('indicator/profile', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Profile' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Indicator' => array('km/updateIndicator', 'id' => $indicator->id),
                        'Update Baseline Data' => array('km/manageBaselineData', 'indicator' => $indicator->id),
                        'Enlist an Indicator' => array('km/enlistIndicator')
                    ))),
            'indicator' => $indicator,
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function updateIndicator() {
        $id = filter_input(INPUT_GET, 'id');
        $this->layout = 'column-1';
        if (!isset($id) || empty($id)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicator = $this->indicatorService->retrieveIndicator($id);
        if (is_null($indicator->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        }

        $this->title = ApplicationConstants::APP_NAME . ' - Indicator Profile';
        $this->render('indicator/mainForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Profile' => array('km/indicatorProfile', 'id' => $indicator->id),
                'Update Indicator' => 'active'),
            'indicator' => $indicator
        ));
    }

    public function renderIndicatorBaselineTable() {
        $id = filter_input(INPUT_POST, 'id');
        $action = filter_input(INPUT_POST, 'action');

        if ((!isset($id) || empty($id)) && (!isset($action))) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $data = array();
        $indicator = $this->indicatorService->retrieveIndicator($id);
        foreach ($indicator->baselineData as $baseline) {
            if ($action != 0) {
                $actionLink = ApplicationUtils::generateLink(array('km/updateBaselineData', 'id' => $baseline->id, 'indicator' => $indicator->id), 'Update') . '&nbsp;|&nbsp;' .
                        ApplicationUtils::generateLink(array('km/confirmDeleteBaselineData', 'id' => $baseline->id, 'indicator' => $indicator->id), 'Delete');
            }
            array_push($data, array(
                'group' => is_null($baseline->baselineDataGroup) ? "-" : $baseline->baselineDataGroup,
                'year' => $baseline->coveredYear,
                'figure' => $baseline->value,
                'action' => isset($actionLink) ? $actionLink : ''));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function update() {
        $indicatorData = filter_input_array(INPUT_POST)['Indicator'];
        $uomData = filter_input_array(INPUT_POST)['UnitOfMeasure'];

        if (count(filter_input_array(INPUT_POST)) < 2) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicator = new Indicator();
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'unitofmeasure' => $uomData
        ));

        if ($indicator->validate()) {
            $this->indicatorService->updateIndicator($indicator);
            $_SESSION['notif'] = array('class' => 'info', 'message' => 'Indicator successfully updated');
            $this->redirect(array('km/indicatorProfile', 'id' => $indicator->id));
        } else {
            $_SESSION['validation'] = $indicator->validationMessages;
            $this->redirect(array('km/enlistIndicator'));
        }
    }

    public function manageBaselineData() {
        $indicator = filter_input(INPUT_GET, 'indicator');

        if (!isset($indicator) || empty($indicator)) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicatorObject = $this->indicatorService->retrieveIndicator($indicator);

        if (is_null($indicatorObject->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        }

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Baseline Data';
        $this->layout = 'column-1';

        $this->render('indicator/baselineForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Profile' => array('km/indicatorProfile', 'id' => $indicatorObject->id),
                'Manage Baseline Data' => 'active'),
            'indicatorId' => $indicatorObject->id,
            'uom' => $indicatorObject->uom->description,
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

    public function insertBaseline() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicatorData = filter_input_array(INPUT_POST)['Indicator'];
        $baselineData = filter_input_array(INPUT_POST)['Baseline'];

        $indicator = new Indicator();
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'baseline' => $baselineData
        ));
        if ($indicator->baselineData->validate()) {
            try {
                $this->indicatorService->addBaselineDataToIndicator($indicator);
                $_SESSION['notif'] = array('class' => 'success', 'message' => 'Baseline data added');
            } catch (ServiceException $ex) {
                $_SESSION['validation'] = array($ex->getMessage());
            }
        } else {
            $_SESSION['validation'] = $indicator->baselineData->validationMessages;
        }
        $this->redirect(array('km/manageBaselineData', 'indicator' => $indicator->id));
    }

    public function updateBaselineData() {
        $id = filter_input(INPUT_GET, 'id');
        $indicator = filter_input(INPUT_GET, 'indicator');

        $condition = (!isset($id) || empty($id)) && (!isset($indicator) || empty($indicator));
        if ($condition) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicatorEntity = $this->indicatorService->retrieveIndicator($indicator);
        if (is_null($indicatorEntity->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        } else {
            $baseline = $this->indicatorService->getBaselineDataFromIndicator($indicatorEntity, $id);
            if (is_null($baseline->id)) {
                $_SESSION['notif'] = array('class' => '', 'message' => 'Baseline data not found');
                $this->redirect(array('km/manageBaselineData', 'indicator' => $indicatorEntity->id));
            }
            $this->title = ApplicationConstants::APP_NAME . ' - Manage Baseline Data';
            $this->layout = 'column-1';
            $this->render('indicator/baselineForm', array(
                'breadcrumb' => array(
                    'Home' => array('site/index'),
                    'Knowledge Management' => array('km/index'),
                    'Manage Indicators' => array('km/indicators'),
                    'Profile' => array('km/indicatorProfile', 'id' => $indicatorEntity->id),
                    'Manage Baseline Data' => array('km/manageBaselineData', 'indicator' => $indicatorEntity->id),
                    'Update Baseline Data' => 'active'),
                'uom' => $indicatorEntity->uom->description,
                'indicatorId' => $indicatorEntity->id,
                'baseline' => $baseline
            ));
        }
    }

    public function updateBaseline() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $indicatorData = filter_input_array(INPUT_POST)['Indicator'];
        $baselineData = filter_input_array(INPUT_POST)['Baseline'];

        $indicator = new Indicator();
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'baseline' => $baselineData
        ));
        if ($indicator->baselineData->validate()) {
            $this->indicatorService->updateBaseline($indicator->baselineData);
            $_SESSION['notif'] = array('class' => 'info', 'message' => 'Baseline data updated');
        } else {
            $_SESSION['validation'] = $indicator->baselineData->validationMessages;
        }
        $this->redirect(array('km/manageBaselineData', 'indicator' => $indicator->id));
    }

    public function confirmDeleteBaselineData() {
        $id = filter_input(INPUT_GET, 'id');
        $indicatorId = filter_input(INPUT_GET, 'indicator'); 
        
        $condition = (!isset($id) || empty($id)) && (!isset($indicatorId) || empty($indicatorId));
        if ($condition) {
            throw new ValidationException('Another parameter is needed to process this request');
        }
        $indicator = $this->indicatorService->retrieveIndicator($indicatorId);
        if (is_null($indicator->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        } else {
            $baseline = $this->indicatorService->getBaselineDataFromIndicator($indicator, $id);
            if (is_null($baseline->id)) {
                $_SESSION['notif'] = array('class' => '', 'message' => 'Baseline data not found');
                $this->redirect(array('km/manageBaselineData', 'indicator' => $indicator->id));
            }
            $this->title = ApplicationConstants::APP_NAME . ' - Delete Baseline Data';
            $this->layout = 'column-1';
            $this->render('commons/confirm', array(
                'breadcrumb' => array(
                    'Home' => array('site/index'),
                    'Knowledge Management' => array('km/index'),
                    'Manage Indicators' => array('km/indicators'),
                    'Profile' => array('km/indicatorProfile', 'id' => $indicator->id),
                    'Manage Baseline Data' => array('km/manageBaselineData', 'indicator' => $indicator->id),
                    'Delete Baseline Data' => 'active'),
                'indicator' => $indicator->id,
                'confirm'=> array(
                    'header'=>'Remove Baseline Data',
                    'text'=>"Do you want to remove this baseline data?&nbsp;Data are as follows<br/>"
                    . "<strong>Item:&nbsp;</strong>{$baseline->baselineDataGroup}<br/>"
                    . "<strong>Covered Year:&nbsp;</strong>{$baseline->coveredYear}<br/>"
                    . "<strong>Figure Value:&nbsp;</strong>{$baseline->value}&nbsp{$indicator->uom->description}",
                    'accept.url'=>array('km/deleteBaseline', 'id'=>$baseline->id, 'indicator'=>$indicator->id),
                    'accept.text'=>'Continue',
                    'accept.class'=>'red',
                    'deny.url'=>array('km/manageBaselineData', 'indicator'=>$indicator->id),
                    'deny.text'=>'Back',
                    'deny.class'=>'grey'),
            ));
        }
    }
    
    public function deleteBaseline(){
        $id = filter_input(INPUT_GET, 'id');
        $indicatorId = filter_input(INPUT_GET, 'indicator'); 
        
        $condition = (!isset($id) || empty($id)) && (!isset($indicatorId) || empty($indicatorId));
        if ($condition) {
            throw new ValidationException('Another parameter is needed to process this request');
        }
        $indicator = $this->indicatorService->retrieveIndicator($indicatorId);
        if (is_null($indicator->id)) {
            $_SESSION['notif'] = array('class' => '', 'message' => 'Indicator not found');
            $this->redirect(array('km/indicators'));
        } else {
            $baseline = $this->indicatorService->getBaselineDataFromIndicator($indicator, $id);
            if (is_null($baseline->id)) {
                $_SESSION['notif'] = array('class' => '', 'message' => 'Baseline data not found');
                $this->redirect(array('km/manageBaselineData', 'indicator' => $indicator->id));
            }
        }
        $this->indicatorService->unlinkBaseline($id);
        $_SESSION['notif']=array('class'=>'error', 'message'=>'Baseline data deleted');
        $this->redirect(array('km/manageBaselineData', 'indicator' => $indicator->id));
    }

}
