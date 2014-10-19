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
    
    private $db = null;
    public function __construct() {
        $this->db = new PositionDao();
    }
    
    public function listPositions() {
        $positions = $this->db->listPositions();
        if(count($positions) == 0){
            throw new ServiceException('Positions not defined properly.');
        }
        return $positions;
    }
}
