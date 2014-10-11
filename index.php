<?php
namespace org\csflu\isms\views;

require_once 'protected/applicationLoader.php';

use org\csflu\isms\core\Application as Application;

$application = new Application();
$application->runApplication();