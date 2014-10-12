<?php

namespace org\csflu\isms\core;

use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;

class ConnectionManager {

    private static $db = null;

    private function __construct() {
        
    }

    /**
     * @return \PDO
     */
    public static function getConnectionInstance() {
        if (is_null(self::$db)) {
            self::$db = new \PDO(ApplicationConstants::DATABASE_DSN, 
                                 ApplicationConstants::DATABASE_USER, 
                                 ApplicationConstants::DATABASE_KEY, 
                                 array(\PDO::ATTR_PERSISTENT => true,
                                       \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        return self::$db;
    }

}
