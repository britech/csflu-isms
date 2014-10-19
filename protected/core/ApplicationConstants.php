<?php
namespace org\csflu\isms\core;

class ApplicationConstants {
	const APP_NAME = "CSFLU Integrated Strategy Management System";
	
	#Database-related settings
	#Main Database
	const DATABASE_DSN = "mysql:dbname=capstone;host=localhost";
	const DATABASE_USER = "root";
	const DATABASE_KEY = "";

	#HR Database
	const DATABASE_DSN_HR = "mysql:dbname=csflu_hr;host=localhost";
	const DATABASE_USER_HR = "root";
	const DATABASE_KEY_HR = "";

}
?>