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
     * @return Position[]
     * @throws ServiceException
     */
    public function listPositions();
    
    /**
     * @param Position $position
     * @throws ServiceException
     */
    public function managePosition($position);
}
