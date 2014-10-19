<?php

namespace org\csflu\isms\dao\commons;

use org\csflu\isms\models\commons\Position as Position;
use org\csflu\isms\exceptions\DataAccessException as DataAccessException;
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
}
