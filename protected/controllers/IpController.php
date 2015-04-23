<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\controllers\support\CommitmentModuleSupport;

/**
 * Description of IpController
 *
 * @author britech
 */
class IpController extends Controller {

    private $commitmentModuleSupport;

    public function __construct() {
        $this->checkAuthorization();
        $this->commitmentModuleSupport = CommitmentModuleSupport::getInstance($this);
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
            'commitments' => $this->commitmentModuleSupport->listCommitments($account)
        ));
    }
    
}
