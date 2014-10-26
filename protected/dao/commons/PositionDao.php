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
     * @param Position $position
     * @throws DataAccessException
     */
    public function enlistPosition($position);
}
