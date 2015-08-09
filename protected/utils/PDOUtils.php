<?php

namespace org\csflu\isms\util;


class PDOUtils {

    private function __construct() {
        
    }

    /**
     * Checks an existing transaction before creating a new database transaction
     * @param \PDO $db
     */
    public static function initiateTransaction(\PDO $db){
        if(!$db->inTransaction()){
            $db->beginTransaction();
        }
    }
    
    /**
     * Checks an existing transaction before committing the SQL statement
     * @param \PDO $db
     */
    public static function commitTransaction(\PDO $db){
        if($db->inTransaction()){
            $db->commit();
        }
    }
    
    /**
     * Checks an existing transaction before a SQL statement is rolled-back
     * @param \PDO $db
     */
    public static function rollbackTransaction(\PDO $db){
        if($db->inTransaction()){
            $db->rollBack();
        }
    }
    
}
