<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;
use org\csflu\isms\service\reports\IpReportServiceImpl;
use org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\ubt\UnitBreakthrough;

/**
 * Description of IpController
 *
 * @author britech
 */
class IpController extends Controller {

    private $commitmentModuleSupport;
    private $reportService;
    private $ubtService;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($this);
        $this->reportService = new IpReportServiceImpl();
        $this->ubtService = new UnitBreakthroughManagementServiceSimpleImpl();
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
            'ubtModel' => new UnitBreakthrough(),
            'employee' => "{$model->user->employee->lastName}, {$model->user->employee->givenName}",
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function validateReportInput() {
        try {
            $this->validatePostData(array('IpReportInput', 'UnitBreakthrough', 'UserAccount'));
        } catch (ControllerException $ex) {
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
            $this->logger->error($ex->getMessage(), $ex);
        }

        $reportInputData = $this->getFormData('IpReportInput');
        $userAccountData = $this->getFormData('UserAccount');
        $ubtData = $this->getFormData('UnitBreakthrough');

        $reportInput = new IpReportInput();
        $reportInput->bindValuesUsingArray(array(
            'ipreportinput' => $reportInputData,
            'user' => $userAccountData,
            'unitbreakthrough' => $ubtData
        ));

        $this->remoteValidateModel($reportInput);
    }

    public function generateReport() {
        $this->validatePostData(array('IpReportInput', 'UnitBreakthrough', 'UserAccount'));

        $reportInputData = $this->getFormData('IpReportInput');
        $userAccountData = $this->getFormData('UserAccount');
        $ubtData = $this->getFormData('UnitBreakthrough');

        $reportInput = new IpReportInput();
        $reportInput->bindValuesUsingArray(array(
            'ipreportinput' => $reportInputData,
            'user' => $userAccountData,
            'unitbreakthrough' => $ubtData
        ));

        $reportInput->unitBreakthrough = $this->ubtService->getUnitBreakthrough($reportInput->unitBreakthrough->id);

        if (!$reportInput->validate()) {
            $this->setSessionData('validation', $reportInput->validationMessages);
            $this->redirect(array('ip/report'));
            return;
        }

        try {
            $data = $this->reportService->retrieveData($reportInput);
            $detailData = $this->reportService->retrieveBreakdownData($reportInput);

            $this->render('ip/generate', array(
                'user' => $this->commitmentModuleSupport->loadAccountModel(),
                'input' => $reportInput,
                'data' => $data,
                'detail' => $detailData
            ));
        } catch (ServiceException $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('ip/report'));
        }
    }

}
