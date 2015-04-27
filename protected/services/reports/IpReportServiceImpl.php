<?php

namespace org\csflu\isms\service\reports;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\service\reports\IpReportService;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\dao\reports\IpReportDaoSqlImpl;
use org\csflu\isms\dao\ubt\WigSessionDaoSqlmpl;
use org\csflu\isms\models\reports\IpReportOutput;
use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\uam\UserAccount;

/**
 * Description of IpReportServiceImpl
 *
 * @author britech
 */
class IpReportServiceImpl implements IpReportService {

    private $reportDaoSource;
    private $wigDaoSource;
    private $logger;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->reportDaoSource = new IpReportDaoSqlImpl();
        $this->wigDaoSource = new WigSessionDaoSqlmpl();
    }

    public function retrieveData(IpReportInput $input) {
        $commitments = $this->reportDaoSource->listCommitments($input);

        if (count($commitments) == 0) {
            throw new ServiceException("No data retrieved from parameters");
        }

        $output = new IpReportOutput($commitments);

        return $output;
    }

    public function retrieveBreakdownData(IpReportInput $input) {
        $wigSessions = $this->wigDaoSource->listWigSessions($input->unitBreakthrough);

        if (count($wigSessions) == 0) {
            throw new ServiceException("No data retrieved from parameters");
        }

        $outputs = array();
        foreach ($wigSessions as $wigSession) {
            $output = new IpReportOutput($this->loadCommitments($wigSession->commitments, $input->user), $wigSession);
            array_push($outputs, $output);
        }
        return $outputs;
    }

    /**
     * Filters the Commitment based on the user
     * @param Commitment[] $commitments
     * @return Commitment[]
     */
    private function loadCommitments(array $commitments, UserAccount $userAccount) {
        $output = array();
        
        foreach($commitments as $commitment){
            if($commitment->user->id == $userAccount->id){
               $output = array_merge($output, array($commitment));
            }
        }
        
        return $commitments;
    }

}
