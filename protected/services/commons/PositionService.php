<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\exceptions\ServiceException as ServiceException;
use org\csflu\isms\models\commons\Position as Position;
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
}
