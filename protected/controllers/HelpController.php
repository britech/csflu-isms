<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;

/**
 * Description of HelpController
 *
 * @author britech
 */
class HelpController extends Controller {

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = "help-layout";
        $this->title = ApplicationConstants::APP_NAME . " - Help Module";
    }

    public function index() {
        $this->render('help/index', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_links'
            )
        ));
    }

    public function login() {
        $this->render('help/login', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => array('help/index'),
                'Login' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_login-links'
            )
        ));
    }

    public function indicator() {
        $this->render('help/indicator', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => array('help/index'),
                'Indicator' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_indicator-links'
            )
        ));
    }

}
