<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\models\commons\Position;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface PositionDao {
    
    /**
     * @return Position[]
     * @throws DataAccessException
     */
    public function listPositions();
    
    /**
     * @param Integer $id
     * @return Position
     */
    public function getPositionData($id);
    
    /**
     * @param Position $position
     * @throws DataAccessException
     */
    public function enlistPosition(Position $position);
    
    /**
     * @param Position $position
     * @throws DataAccessException
     */
    public function updatePosition(Position $position);
}
