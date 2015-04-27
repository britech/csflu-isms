<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\reports\IpReportOutput;
/**
 *
 * @author britech
 */
interface IpReportService {
    
    /**
     * Retrieves the Commitment data for report generation
     * @param IpReportInput $input
     * @return IpReportOutput
     * @throws ServiceException
     */
    public function retrieveData(IpReportInput $input);
    
    /**
     * Retrives the WigSession data for breakdown report generation
     * @param IpReportInput $input
     * @return IpReportOutput[]
     * @throws ServiceException
     */
    public function retrieveBreakdownData(IpReportInput $input);
}
