<?php
namespace org\csflu\isms\views;

require_once dirname(__FILE__) . '/protected/core/Application.php';

use org\csflu\isms\core\Application;

$application = Application::getInstance("config");
$application->runApplication();