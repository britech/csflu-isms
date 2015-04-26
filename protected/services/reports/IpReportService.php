<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\ubt\Commitment;
/**
 *
 * @author britech
 */
interface IpReportService {
    
    /**
     * Retrieves the Commitment data for report generation
     * @param IpReportInput $input
     * @return Commitment[]
     * @throws ServiceException
     */
    public function retrieveData(IpReportInput $input);
}
