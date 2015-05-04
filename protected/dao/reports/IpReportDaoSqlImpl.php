<?php

namespace org\csflu\isms\dao\reports;

use org\csflu\isms\dao\reports\IpReportDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\models\reports\IpReportInput;
use org\csflu\isms\models\ubt\Commitment;

/**
 * Description of IpReportDaoSqlImpl
 *
 * @author britech
 */
class IpReportDaoSqlImpl implements IpReportDao {

    private $db;
    private $wigDao;
    private $logger;

    public function __construct() {
        $this->db = ConnectionManager::getConnectionInstance();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function listCommitments(IpReportInput $input) {
        try {
            $dbst = $this->db->prepare('SELECT commit_id, t1.status FROM commitments_main t1 JOIN ubt_wig t2 ON wig_ref=wig_id WHERE user_ref=:user AND ubt_ref=:ubt AND (period_start_date>=:start AND period_end_date<=:end)');
            $dbst->execute(array(
                'user' => $input->user->id,
                'ubt' => $input->unitBreakthrough->id,
                'start' => $input->startingPeriod->format('Y-m-d'),
                'end' => $input->endingPeriod->format('Y-m-d')
            ));

            $commitments = array();
            while ($data = $dbst->fetch()) {
                $commitment = new Commitment();
                list($commitment->id, $status) = $data;
                $commitment->commitmentEnvironmentStatus = strtoupper($status);
                $commitments = array_merge($commitments, array($commitment));
            }
            return $commitments;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }
}
