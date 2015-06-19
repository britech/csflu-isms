<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;

/**
 * Description of LeadMeasure
 *
 * @author britech
 */
class LeadMeasureController extends Controller {

    private $modelLoaderUtil;
    private $ubtService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->isRbacEnabled = true;
        $this->moduleCode = ModuleAction::MODULE_UBT;
        $this->actionCode = "UBTM";
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($ubt) {
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel($ubt);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, null, null, null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Measures';
        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');
        $model = new LeadMeasure();
        $model->startingPeriod = $unitBreakthrough->startingPeriod;
        $model->endingPeriod = $unitBreakthrough->endingPeriod;
        $this->render('ubt/lead-measures', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Manage Lead Measures' => 'active'),
            'model' => $model,
            'ubtModel' => $unitBreakthrough,
            'uomModel' => new UnitOfMeasure(),
            'designationList' => LeadMeasure::listDesignationTypes(),
            'statusList' => LeadMeasure::listEnvironmentStatus(),
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function validateInput() {
        $this->validatePostData(array('LeadMeasure', 'UnitOfMeasure'), true);
        $leadMeasureData = $this->getFormData('LeadMeasure');
        $uomData = $this->getFormData('UnitOfMeasure');

        $leadMeasure = new LeadMeasure();
        $leadMeasure->bindValuesUsingArray(array(
            'leadmeasure' => $leadMeasureData,
            'uom' => $uomData
        ));

        $this->remoteValidateModel($leadMeasure);
    }

    public function insert() {
        $this->validatePostData(array('LeadMeasure', 'UnitOfMeasure', 'UnitBreakthrough'));

        $leadMeasureData = $this->getFormData('LeadMeasure');
        $uomData = $this->getFormData('UnitOfMeasure');
        $unitBreakthroughData = $this->getFormData('UnitBreakthrough');

        $leadMeasure = new LeadMeasure();
        $leadMeasure->bindValuesUsingArray(array(
            'leadmeasure' => $leadMeasureData,
            'uom' => $uomData
        ));
        $leadMeasure->uom = $this->modelLoaderUtil->loadUomModel($leadMeasure->uom->id);
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel($unitBreakthroughData['id']);

        if (!$leadMeasure->validate()) {
            $this->setSessionData('validation', $leadMeasure->validationMessages);
            $this->redirect(array('leadMeasure/index', 'ubt' => $unitBreakthrough->id));
            return;
        }

        $unitBreakthrough->leadMeasures = array($leadMeasure);

        try {
            $leadMeasures = $this->ubtService->insertLeadMeasures($unitBreakthrough);
            foreach ($leadMeasures as $data) {
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $data);
            }
            $this->setSessionData('notif', array('class' => 'success', 'message' => 'LeadMeasure successfully added'));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->redirect(array('leadMeasure/index', 'ubt' => $unitBreakthrough->id));
    }

    public function listEntry() {
        $this->validatePostData(array('ubt'));
        $id = $this->getFormData('ubt');

        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel($id);
        $data = array();
        foreach ($unitBreakthrough->leadMeasures as $leadMeasure) {
            array_push($data, array(
                'description' => $leadMeasure->description,
                'status' => $leadMeasure->translateEnvironmentStatus(),
                'actions' => $this->resolveLeadMeasureActionLinks($leadMeasure)
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function resolveLeadMeasureActionLinks(LeadMeasure $leadMeasure) {
        $links = array(ApplicationUtils::generateLink(array('leadMeasure/update', 'id' => $leadMeasure->id), 'Update'));
        if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE) {
            $links = array_merge($links, array(ApplicationUtils::generateLink('#', 'Disable', array('id' => "disable-{$leadMeasure->id}"))));
        } elseif ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_INACTIVE) {
            $links = array_merge($links, array(ApplicationUtils::generateLink('#', 'Enable', array('id' => "enable-{$leadMeasure->id}"))));
        }
        return implode('&nbsp;|&nbsp;', $links);
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('LeadMeasure', 'UnitOfMeasure'));
            $this->processUpdate();
        }
        $model = $this->modelLoaderUtil->loadLeadMeasureModel($id);
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel(null, $model);
        $strategyMap = $this->modelLoaderUtil->loadMapModel(null, null, null, null, null, $unitBreakthrough);

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Measures';
        $unitBreakthrough->startingPeriod = $unitBreakthrough->startingPeriod->format('Y-m-d');
        $unitBreakthrough->endingPeriod = $unitBreakthrough->endingPeriod->format('Y-m-d');
        $model->startingPeriod = $model->startingPeriod->format('Y-m-d');
        $model->endingPeriod = $model->endingPeriod->format('Y-m-d');

        $this->render('ubt/lead-measures', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'UBT Directory' => array('ubt/index', 'map' => $strategyMap->id),
                'Unit Breakthrough' => array('ubt/view', 'id' => $unitBreakthrough->id),
                'Manage Lead Measures' => array('leadMeasure/index', 'ubt' => $unitBreakthrough->id),
                'Update' => 'active'),
            'model' => $model,
            'ubtModel' => $unitBreakthrough,
            'uomModel' => $model->uom,
            'designationList' => LeadMeasure::listDesignationTypes(),
            'statusList' => LeadMeasure::listEnvironmentStatus(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    private function processUpdate() {
        $leadMeasureData = $this->getFormData('LeadMeasure');
        $uomData = $this->getFormData('UnitOfMeasure');

        $leadMeasure = new LeadMeasure();
        $leadMeasure->bindValuesUsingArray(array(
            'leadmeasure' => $leadMeasureData,
            'uom' => $uomData
        ));
        $leadMeasure->uom = $this->modelLoaderUtil->loadUomModel($leadMeasure->uom->id);

        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel(null, $leadMeasure);

        if (!$leadMeasure->validate()) {
            $this->setSessionData('validation', $leadMeasure->validationMessages);
            $this->redirect(array('leadMeasure/index', 'ubt' => $unitBreakthrough->id));
            return;
        }

        $oldLeadMeasure = $this->modelLoaderUtil->loadLeadMeasureModel($leadMeasure->id);
        if ($leadMeasure->computePropertyChanges($oldLeadMeasure) > 0) {
            try {
                $this->ubtService->updateLeadMeasure($leadMeasure);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $leadMeasure, $oldLeadMeasure);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Lead Measure successfully updated'));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        }
        $this->redirect(array('leadMeasure/index', 'ubt' => $unitBreakthrough->id));
    }

    public function updateStatus() {
        $this->validatePostData(array('LeadMeasure'), true);

        $leadMeasureData = $this->getFormData('LeadMeasure');
        $id = $leadMeasureData['id'];
        $status = $leadMeasureData['leadMeasureEnvironmentStatus'];

        $leadMeasure = $this->modelLoaderUtil->loadLeadMeasureModel($id, array('remote' => true));
        $leadMeasure->leadMeasureEnvironmentStatus = $status;
        $unitBreakthrough = $this->modelLoaderUtil->loadUnitBreakthroughModel(null, $leadMeasure, null, array('remote' => true));
        
        $oldModel = $this->modelLoaderUtil->loadLeadMeasureModel($id, array('remote' => true));
        try {
            $this->ubtService->updateLeadMeasureStatus($leadMeasure);
            $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_UBT, $unitBreakthrough->id, $leadMeasure, $oldModel);
            $this->setSessionData('notif', array('class' => 'info', 'message' => "Lead Measure - {$leadMeasure->description} is now set to {$leadMeasure->translateEnvironmentStatus()}"));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
        }
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('leadMeasure/index', 'ubt' => $unitBreakthrough->id))));
    }

}
