<?php

namespace org\csflu\isms\models\reports;

use org\csflu\isms\models\ubt\Commitment;
use org\csflu\isms\models\ubt\WigSession;

/**
 * Description of IpReportOutput
 * 
 * @author britech
 */
class IpReportOutput {

    private $commitments;
    private $wigSession;

    /**
     * 
     * @param Commitment[] $commitments
     */
    public function __construct(array $commitments, WigSession $wigSession = null) {
        $this->commitments = $commitments;
        $this->wigSession = $wigSession;
    }

    public function countAll() {
        return count($this->commitments);
    }

    public function countPendingCommitments() {
        return $this->countCommitments(Commitment::STATUS_PENDING);
    }

    public function countOngoingCommitments() {
        return $this->countCommitments(Commitment::STATUS_ONGOING);
    }

    public function countFinishedCommitments() {
        return $this->countCommitments(Commitment::STATUS_FINISHED);
    }

    public function countUnfinishedCommitments() {
        return $this->countCommitments(Commitment::STATUS_UNFINISHED);
    }

    public function calculateDistributionPercentage($count) {
        return number_format(($count / $this->countAll() * 100), 2);
    }
    
    /**
     * 
     * @return WigSession
     */
    public function getWigSessionEntity(){
        return $this->wigSession;
    }

    private function countCommitments($status) {
        $counter = 0;

        foreach ($this->commitments as $commitment) {
            if ($commitment->commitmentEnvironmentStatus == $status) {
                $counter++;
            }
        }

        return $counter;
    }

}
