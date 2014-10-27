<?php
namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;

class KmController extends Controller {
	
	public function __construct(){
		$this->checkAuthorization();
		$this->layout = 'column-2';
	}

	public function index(){
		$this->title = ApplicationConstants::APP_NAME.' - Knowledge Management';
		$this->render('km/index', array(
			'breadcrumb'=>array(
				'Home'=>array('site/index'),
				'Knowledge Management'=>'active'),
			'sidebar'=>array(
				'data'=>array(
					'header'=>'Knowledge Management',
					'links'=>array(
						'Governance Indicators'=>array('indicator/governanceList'),
						'Job Position Indicators'=>array('indicator/positionList'),
						'Generate Reports'=>array('km/reportsList')
				)
			))
		));
	}
}
?>