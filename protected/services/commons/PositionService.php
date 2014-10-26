<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\commons\Position;
/**
 *
 * @author britech
 */
interface PositionService {
   
    /**
     * Retrieves the available positions
     * @return Position[]
     * @throws ServiceException
     */
    public function listPositions();
    
    /**
     * Retrieves the position
     * @param Integer $id
     * @return Position
     * @throws ServiceException
     */
    public function getPositionData($id);
    
    /**
     * Enlist/Update a Position
     * @param Position $position
     * @throws ServiceException
     */
    public function managePosition($position);
}
