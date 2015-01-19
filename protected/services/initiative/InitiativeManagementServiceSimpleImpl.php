<?php

namespace org\csflu\isms\service\initiative;

use org\csflu\isms\models\map\StrategyMap;
use org\csflu\isms\models\initiative\Initiative;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\initiative\InitiativeManagementService;
use org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl as InitiativeDao;

/**
 * Description of InitiativeManagementServiceSimpleImpl
 *
 * @author britech
 */
class InitiativeManagementServiceSimpleImpl implements InitiativeManagementService {

    private $daoSource;

    public function __construct() {
        $this->daoSource = new InitiativeDao();
    }

    public function listInitiatives(StrategyMap $strategyMap) {
        return $this->daoSource->listInitiatives($strategyMap);
    }

    public function addInitiative(Initiative $initiative, StrategyMap $strategyMap) {
        $initiatives = $this->daoSource->listInitiatives($strategyMap);
        
        $found = false;
        foreach($initiatives as $data){
            if($data->title == $initiative->title){
                $found = true;
                break;
            }
        }
        
        if($found){
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

}
