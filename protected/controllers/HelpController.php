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

    public function strategyMap() {
        $this->render('help/map', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => array('help/index'),
                'Strategy Map' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_map-links'
            )
        ));
    }

    public function measureProfile() {
        $this->render('help/measure-profile', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => array('help/index'),
                'Measure Profile' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_mp-links'
            )
        ));
    }

    public function initiative() {
        $this->render('help/initiative', array(
            self::COMPONENT_BREADCRUMB => array(
                'Help Contents' => array('help/index'),
                'Initiative' => 'active'
            ),
            self::COMPONENT_SIDEBAR => array(
                self::SUB_COMPONENT_SIDEBAR_FILE => 'help/_initiative-links'
            )
        ));
    }

}
