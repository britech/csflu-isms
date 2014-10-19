<?php

namespace org\csflu\isms\core;

use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\exceptions\DataAccessException;
class ConnectionManager {

    private static $db = null;
    private static $dbHr = null;

    private function __construct() {
        
    }

    /**
     * The database connection to the main repository
     * @return \PDO
     * @throws DataAccessException
     */
    public static function getConnectionInstance() {
        if (is_null(self::$db)) {
            try{
                self::$db = new \PDO(ApplicationConstants::DATABASE_DSN, 
                                 ApplicationConstants::DATABASE_USER, 
                                 ApplicationConstants::DATABASE_KEY, 
                                 array(\PDO::ATTR_PERSISTENT => true,
                                       \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            } catch (\PDOException $ex) {
                throw new DataAccessException($ex->getMessage());
            }
        }
        return self::$db;
    }

    /**
     * Additional database connection to the HR repository
     * @return \PDO
     * @throws DataAccessException
     */
    public static function getHrConnectionInstance(){
        if (is_null(self::$dbHr)) {
            try{
                self::$dbHr = new \PDO(ApplicationConstants::DATABASE_DSN_HR, 
                                 ApplicationConstants::DATABASE_USER_HR, 
                                 ApplicationConstants::DATABASE_KEY_HR, 
                                 array(\PDO::ATTR_PERSISTENT => true,
                                       \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            } catch (\PDOException $ex) {
                throw new DataAccessException($ex->getMessage());
            }
        }
        return self::$dbHr;
    }

}
