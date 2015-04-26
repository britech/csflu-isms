<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\reports\IpReportService;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\dao\reports\IpReportDaoSqlImpl;

/**
 * Description of IpReportServiceImpl
 *
 * @author britech
 */
class IpReportServiceImpl implements IpReportService {

    private $reportDaoSource;

    public function __construct() {
        $this->reportDaoSource = new IpReportDaoSqlImpl();
    }

    public function retrieveData(IpReportInput $input) {
        $commitments = $this->reportDaoSource->retrieveData($input);

        if (count($commitments) == 0) {
            throw new ServiceException("No data retrieved from parameters");
        }
        return $commitments;
    }

}
