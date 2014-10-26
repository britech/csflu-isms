<?php

namespace org\csflu\isms\service\commons;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\commons\UnitOfMeasure;
/**
 *
 * @author britech
 */
interface UnitOfMeasureService {
    
    /**
     * Retrieves the list of available units of measures
     * @return UnitOfMeasure[]
     * @throws ServiceException 
     */
    public function listUnitOfMeasures();
}
