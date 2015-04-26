<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;
use org\csflu\isms\service\reports\IpReportServiceImpl;
use org\csflu\isms\models\reports\IpReportInput;

/**
 * Description of IpController
 *
 * @author britech
 */
class IpController extends Controller {

    private $commitmentModuleSupport;
    private $reportService;

    public function __construct() {
        $this->checkAuthorization();
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($this);
        $this->reportService = new IpReportServiceImpl();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Performance Scorecard';
        $this->layout = "column-2";

        $account = $this->commitmentModuleSupport->loadAccountModel();
        $this->render('ip/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => 'active'
            ),
            'sidebar' => array(
                'file' => 'ip/_profile'
            ),
            'account' => $account,
            'commitments' => $this->commitmentModuleSupport->listCommitments($account),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function report() {
        $this->title = ApplicationConstants::APP_NAME . ' - Generate Scorecard';

        $model = new IpReportInput();
        $model->user = $this->commitmentModuleSupport->loadAccountModel();
        $this->render('ip/report', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Performance Scorecard' => array('ip/index'),
                'Generate Scorecard' => 'active'
            ),
            'model' => $model,
            'employee' => "{$model->user->employee->lastName}, {$model->user->employee->givenName}"
        ));
    }

    public function validateReportInput() {
        try {
            $this->validatePostData(array('IpReportInput', 'UserAccount'));
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        }

        $reportInputData = $this->getFormData('IpReportInput');
        $userAccountData = $this->getFormData('UserAccount');

        $reportInput = new IpReportInput();
        $reportInput->bindValuesUsingArray(array(
            'ipreportinput' => $reportInputData,
            'user' => $userAccountData
        ));
        
        $this->remoteValidateModel($reportInput);
    }

}
