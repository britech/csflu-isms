<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\reports\IpReportOutput;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\uam\UserAccount;

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

    /**
     * Retrieves the commitments for the specified UserAccount entity
     * @param Commitment[] $commitments
     * @param UserAccount $userAccount
     * @return IpReportOutput[]
     */
    public function filterCommitments(array $commitments, UserAccount $userAccount);
}
