<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\service\commons\PositionService;
use org\csflu\isms\dao\commons\PositionDaoSqlImpl as PositionDao;
use org\csflu\isms\exceptions\ServiceException;
/**
 * Description of PositionServiceSimpleImpl
 *
 * @author britech
 */
class PositionServiceSimpleImpl implements PositionService{
    
    private $daoSource = null;
    public function __construct() {
        $this->daoSource = new PositionDao();
    }
    
    public function listPositions() {
        $positions = $this->daoSource->listPositions();
        if(count($positions) == 0){
            throw new ServiceException('Positions not defined properly.');
        }
        return $positions;
    }

    public function managePosition($position) {
        if(is_null($position->id)){
            $this->daoSource->enlistPosition($position);
        } else {
            $this->daoSource->updatePosition($position);
        }
    }

    public function getPositionData($id) {
        return $this->daoSource->getPositionData($id);
    }

}
