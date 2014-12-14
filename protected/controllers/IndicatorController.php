<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl as IndicatorManagementService;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\Baseline;
use org\csflu\isms\models\commons\UnitOfMeasure;

class IndicatorController extends Controller {

    private $indicatorService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->indicatorService = new IndicatorManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function enlist() {
        $this->title = ApplicationConstants::APP_NAME . ' - Enlist An Indicator';
        $this->layout = 'column-1';
        $indicator = new Indicator();
        $indicator->validationMode = Model::VALIDATION_MODE_INITIAL;
        $this->render('indicator/mainForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Enlist An Indicator' => 'active'),
            'model' => $indicator,
            'uomModel' => new UnitOfMeasure,
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('Indicator', 'UnitOfMeasure'));
        $indicatorData = $this->getFormData('Indicator');
        $uomData = $this->getFormData('UnitOfMeasure');

        $indicator = new Indicator();
        $indicator->validationMode = Model::VALIDATION_MODE_INITIAL;
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'unitofmeasure' => $uomData
        ));

        if ($indicator->validate()) {
            try {
                $id = $this->indicatorService->manageIndicator($indicator);
                $this->redirect(array('indicator/view', 'id' => $id));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('indicator/enlist'));
            }
        } else {
            $this->setSessionData('validation', $indicator->validationMessages);
            $this->redirect(array('indicator/enlist'));
        }
    }

    public function validateIndicatorEntry() {
        try {
            $this->validatePostData(array('Indicator', 'UnitOfMeasure', 'mode'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $indicatorData = $this->getFormData('Indicator');
        $uomData = $this->getFormData('UnitOfMeasure');
        $mode = $this->getFormData('mode');

        $indicator = new Indicator();
        $indicator->validationMode = $mode;
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'unitofmeasure' => $uomData
        ));

        $this->remoteValidateModel($indicator);
    }

    public function view($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $this->title = ApplicationConstants::APP_NAME . ' - Indicator Profile';
        $indicator = $this->loadModel($id);

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
                        'Update Indicator' => array('indicator/update', 'id' => $indicator->id),
                        'Update Baseline Data' => array('indicator/manageBaselines', 'indicator' => $indicator->id)
                    ))),
            'indicator' => $indicator,
        ));
    }

    public function listIndicators() {
        $indicators = $this->indicatorService->listIndicators();

        $data = array();
        foreach ($indicators as $indicator) {
            array_push($data, array(
                'description' => ApplicationUtils::generateLink(array('indicator/view', 'id' => $indicator->id), $indicator->description)
            ));
        }

        $this->renderAjaxJsonResponse($data);
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('Indicator', 'UnitOfMeasure'));
            $this->processUpdate();
        } elseif (!isset($id) || empty($id)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }
        $this->layout = 'column-1';
        $indicator = $this->loadModel($id);
        $indicator->validationMode = Model::VALIDATION_MODE_UPDATE;

        $this->title = ApplicationConstants::APP_NAME . ' - Indicator Profile';
        $this->render('indicator/mainForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Profile' => array('indicator/view', 'id' => $indicator->id),
                'Update Indicator' => 'active'),
            'model' => $indicator,
            'uomModel' => $indicator->uom,
            'statusList' => Indicator::getDataSourceDescriptionList(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function manageBaselines($indicator) {
        if (!isset($indicator) || empty($indicator)) {
            throw new ControllerException('Another parameter is needed to process this request');
        }

        $data = $this->loadModel($indicator);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Baseline Data';
        $this->layout = 'column-1';

        $baseline = new Baseline();
        $baseline->validationMode = Model::VALIDATION_MODE_INITIAL;
        $this->render('indicator/baselineForm', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Knowledge Management' => array('km/index'),
                'Manage Indicators' => array('km/indicators'),
                'Profile' => array('indicator/view', 'id' => $data->id),
                'Manage Baseline Data' => 'active'),
            'indicatorModel' => $data,
            'model' => $baseline,
            'uom' => $data->uom->description,
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }
    
    public function insertBaseline(){
        
    }

    public function listBaselines() {
        $this->validatePostData(array('id', 'action'));
        $id = $this->getFormData('id');
        $action = $this->getFormData('action');

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

    private function processUpdate() {
        $indicatorData = $this->getFormData('Indicator');
        $uomData = $this->getFormData('UnitOfMeasure');

        $indicator = new Indicator();
        $indicator->validationMode = Model::VALIDATION_MODE_UPDATE;
        $indicator->bindValuesUsingArray(array(
            'indicator' => $indicatorData,
            'unitofmeasure' => $uomData
        ));

        $oldModel = clone $this->loadModel($indicator->id);

        if ($indicator->validate()) {
            if ($indicator->computePropertyChanges($oldModel) > 0) {
                $this->indicatorService->manageIndicator($indicator);
            }
            $this->redirect(array('indicator/view', 'id' => $indicator->id));
        } else {
            $this->setSessionData('validation', $indicator->validationMessages);
            $this->redirect(array('indicator/update', 'id' => $indicator->id));
        }
    }

    private function loadModel($id) {
        $indicator = $this->indicatorService->retrieveIndicator($id);

        if (is_null($indicator->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Indicator not found'));
            $this->redirect(array('km/indicators'));
        } else {
            return $indicator;
        }
    }

}
