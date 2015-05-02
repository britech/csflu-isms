<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\models\ubt\LeadMeasure;
use org\csflu\isms\models\commons\UnitOfMeasure;
use org\csflu\isms\controllers\support\ModelLoaderUtil;

/**
 * Description of LeadMeasure
 *
 * @author britech
 */
class LeadMeasureController extends Controller {

    private $modelLoaderUtil;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
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
    }

}
