<?php
namespace org\csflu\isms\views;

require_once 'protected/applicationLoader.php';

use org\csflu\isms\core\Application;

$application = Application::getInstance();
$application->runApplication();