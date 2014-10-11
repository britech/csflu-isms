<?php
namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller as Controller;
use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;

use org\csflu\isms\models\Login as Login;

use org\csflu\isms\service\uam\SimpleUserManagementServiceImpl as UserManagementService;
use org\csflu\isms\exceptions\ServiceException as ServiceException;

class SiteController extends Controller{
	
	public function index(){
		if(!empty($_SESSION['userId'])){
			$this->title = ApplicationConstants::APP_NAME.' - Welcome';
			$this->render('site/index');
		} else{
			$this->redirect(array('site/login'));	
		}
	}

	public function login(){
		$this->title = ApplicationConstants::APP_NAME.' - Login';
		
		if(isset($_SESSION['login.notif'])){
			$this->render('site/login', array('login.notif'=>$_SESSION['login.notif']));
			unset($_SESSION['login.notif']);
		} else{
			$this->render('site/login');
		}
	}
	
	public function authenticate(){
		$formValues = filter_input_array(INPUT_POST)['Login'];
		
		$login = new Login();
		$login->bindValuesUsingArray($formValues);
		
		if($login->isValid()){
			try{
				$service = new UserManagementService();
				$service->authenticate($login);
				
			} catch(ServiceException $e){
				$this->title = ApplicationConstants::APP_NAME.' - Error';
				$this->viewErrorPage($e);
			}
		} else{
			$_SESSION['login.notif'] = "Username and password are required fields";
			$this->redirect(array('site/login'));
		}
		
	}
}