<?php

namespace org\csflu\isms\util;

class ApplicationLoggerUtils {

	private function __construct(){

	}

	public static function logSql(\Logger $logger, \PDOStatement $pdoStatement, array $parameters = array()){
		$parameterString = "";
		$i = 0;
		foreach($parameters as $key => $value){
			if($i == (count($parameters) - 1)){
				$parameterString .= "{$key} => {$value}";
			} else {
				$parameterString .= "{$key} => {$value}, ";
			}
			$i++;
		}

		$logger->debug("[{$_SESSION['user']}] Executing SQL Statement: {$pdoStatement->queryString} with values [{$parameterString}]");
	}

	public static function logDebug(\Logger $logger, $message){
		$logger->debug("[{$_SESSION['user']}] {$message}");
	}

	public static function logInfo(\Logger $logger, $message){
		$logger->info("[{$_SESSION['user']}] {$message}");
	}

	public static function logWarning(\Logger $logger, $message){
		$logger->warn("[{$_SESSION['user']}] {$message}");
	}

	public static function logError(\Logger $logger, \Exception $exception){
		$logger->error("[{$_SESSION['user']}] {$exception->getMessage()}", $exception);
	}
}