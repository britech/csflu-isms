<?php

namespace org\csflu\isms\dao\reports;

use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\ubt\Commitment;

/**
 *
 * @author britech
 */
interface IpReportDao {
    
    /**
     * @param IpReportInput $input
     * @return Commitment[]
     * @throws DataAccessException
     */
    public function listCommitments(IpReportInput $input);
}
