<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ApplicationException;
use org\csflu\isms\exceptions\DataAccessException;

class DatabaseConnectionManager {

	private static $instance;
	private $logger;

	private function __construct(){
		$this->logger = \Logger::getLogger(__CLASS__);

		$this->logger->debug("Validating DB variables...");
		$this->logger->debug("DB_DSN existing ==> ". (defined("DB_DSN") ? "YES" : "NO"));
		$this->logger->debug("DB_USERNAME existing ==> ". (defined("DB_USERNAME") ? "YES" : "NO"));
		$this->logger->debug("DB_PASSWORD existing ==> ". (defined("DB_PASSWORD") ? "YES" : "NO"));
		$this->logger->debug("HRDB_DSN existing ==> ". (defined("HRDB_DSN") ? "YES" : "NO"));
		$this->logger->debug("HRDB_USERNAME existing ==> ". (defined("HRDB_USERNAME") ? "YES" : "NO"));
		$this->logger->debug("HRDB_PASSWORD existing ==> ". (defined("HRDB_PASSWORD") ? "YES" : "NO"));

		if(!(defined("DB_DSN") && defined("DB_USERNAME") && defined("DB_PASSWORD") && 
			defined("HRDB_DSN") && defined("HRDB_USERNAME") && defined("HRDB_PASSWORD"))){
			throw new ApplicationException("Database variable setup failure. Check configuration file.");
		}
	}

	/**
	 * Creates a singleton instance of the DatabaseConnectionManager class to access the PDO objects.
	 * 
	 * @return DatabaseConnectionManager
	 */
	public static function getInstance() {
		if(is_null(self::$instance)){
			self::$instance = new DatabaseConnectionManager();
		}
		return self::$instance;
	}

	/**
	 * Creates a PDO instance to access the MAIN database schema
	 *
	 * @return \PDO
	 */
	public function getMainDbConnection() {
		try {
			$db = new \PDO(DB_DSN, DB_USERNAME, DB_PASSWORD,
						   array(\PDO::ATTR_PERSISTENT => true,
                                 \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
			return $db;
		} catch (\PDOException $ex) {
			throw new DataAccessException($ex->getMessage());
		}
	}


	/**
	 * Creates a PDO instance to access the HR database schema
	 *
	 * @return \PDO
	 */
	public function getHrDbConnection() {
		try {
			$db = new \PDO(HRDB_DSN, HRDB_USERNAME, HRDB_PASSWORD,
						   array(\PDO::ATTR_PERSISTENT => true,
                                 \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
			return $db;
		} catch (\PDOException $ex) {
			throw new DataAccessException($ex->getMessage());
		}
	}
}