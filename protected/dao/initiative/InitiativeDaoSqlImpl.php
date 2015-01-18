<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\dao\initiative\InitiativeDao;

/**
 * Description of InitiativeDaoSqlImpl
 *
 * @author britech
 */
class InitiativeDaoSqlImpl implements InitiativeDao {

    private $db;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        try {
            $dbst = $this->db->prepare('SELECT ini_id, ini_name FROM ini_main WHERE map_ref=:ref');
            $dbst->execute(array('ref' => $strategyMap->id));

            $initiatives = array();
            while ($data = $dbst->fetch()) {
                $initiative = new Initiative();
                list($initiative->id, $initiative->title) = $data;
                array_push($initiatives, $initiative);
            }
            return $initiatives;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

}
