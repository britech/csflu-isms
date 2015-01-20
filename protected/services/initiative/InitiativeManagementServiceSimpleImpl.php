<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\models\initiative\ImplementingOffice;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\initiative\InitiativeManagementService;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl as InitiativeDao;
use org\csflu\isms\dao\map\StrategyMapDaoSqlImpl as StrategyMapDao;

/**
 * Description of InitiativeManagementServiceSimpleImpl
 *
 * @author britech
 */
class InitiativeManagementServiceSimpleImpl implements InitiativeManagementService {

    private $daoSource;
    private $mapDaoSource;

    public function __construct() {
        $this->daoSource = new InitiativeDao();
        $this->mapDaoSource = new StrategyMapDao();
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        return $this->daoSource->listInitiatives($strategyMap);
    }

    public function addInitiative(Initiative $initiative, StrategyMap $strategyMap) {
        $initiatives = $this->daoSource->listInitiatives($strategyMap);

        $found = false;
        foreach ($initiatives as $data) {
            if ($data->title == $initiative->title) {
                $found = true;
                break;
            }
        }

        if ($found) {
            throw new ServiceException("Initiative already defined. Please use the update facility instead");
        }
        $initiative->id = $this->daoSource->insertInitiative($initiative, $strategyMap);
        $this->daoSource->addImplementingOffices($initiative);
        $this->daoSource->linkObjectives($initiative);
        $this->daoSource->linkLeadMeasures($initiative);
        return $initiative->id;
    }

    public function getInitiative($id) {
        return $this->daoSource->getInitiative($id);
    }

    public function updateInitiative(Initiative $initiative) {
        $strategyMap = $this->mapDaoSource->getStrategyMapByInitiative($initiative);
        $initiatives = $this->daoSource->listInitiatives($strategyMap);

        $found = false;
        foreach ($initiatives as $data) {
            if ($data->id != $initiative->id && $data->title == $initiative->title) {
                $found = true;
                break;
            }
        }

        if ($found) {
            throw new ServiceException("Initiative not updated. Invalid argument data");
        }
        $this->daoSource->updateInitiative($initiative);
    }

    public function addImplementingOffices(Initiative $initiative) {
        $implementingOffices = $this->daoSource->listImplementingOffices($initiative);

        $implementingOfficesToInsert = array();
        foreach ($initiative->implementingOffices as $implementingOffice) {
            $found = false;
            foreach ($implementingOffices as $data) {
                if ($implementingOffice->department->id == $data->department->id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_push($implementingOfficesToInsert, $implementingOffice);
            }
        }
        if (count($implementingOfficesToInsert) == 0) {
            throw new ServiceException("No Implementing Offices added");
        }
        $initiative->implementingOffices = $implementingOfficesToInsert;
        $this->daoSource->addImplementingOffices($initiative);
        return $implementingOfficesToInsert;
    }

    public function deleteImplementingOffice(ImplementingOffice $implementingOffice) {
        $this->daoSource->deleteImplementingOffice($implementingOffice);
    }

    public function getImplementingOffice(Initiative $initiative, $id) {
        $implementingOffices = $this->daoSource->listImplementingOffices($initiative);
        
        foreach ($implementingOffices as $implementingOffice) {
            if($implementingOffice->id ==  $id){
                return $implementingOffice;
            }
        }
    }

}
