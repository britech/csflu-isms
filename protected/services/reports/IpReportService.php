<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\WigSession;
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
    
    /**
     * Retrives the WigSession data for breakdown report generation
     * @param IpReportInput $input
     * @return WigSession[]
     * @throws ServiceException
     */
    public function retrieveBreakdownData(IpReportInput $input);
}
