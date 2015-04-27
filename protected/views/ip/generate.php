<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$pending = $data->countPendingCommitments();
$ongoing = $data->countOngoingCommitments();
$finished = $data->countFinishedCommitments();
$unfinished = $data->countUnfinishedCommitments();
$total = $data->countAll();

$pendingPercentage = $data->calculateDistributionPercentage($pending);
$ongoingPercentage = $data->calculateDistributionPercentage($ongoing);
$finishedPercentage = $data->calculateDistributionPercentage($finished);
$unfinishedPercentage = $data->calculateDistributionPercentage($unfinished);

$breakdownHtml = "";
foreach ($detail as $output) {
$wigSession = $output->getWigSessionEntity();
$breakdownHtml.= <<<ROW
<tr>
    <td style="border: 1px solid #000000; text-align: center">
        {$wigSession->startingPeriod->format('M. j, Y')} - {$wigSession->endingPeriod->format('M. j, Y')}
    </td>
    <td style="border: 1px solid #000000; text-align: center">{$wigSession->listWigMeetingEnvironmentStatus()[$wigSession->wigMeetingEnvironmentStatus]}</td>
    <td style="border: 1px solid #000000; text-align: center">{$output->countPendingCommitments()}</td>
    <td style="border: 1px solid #000000; text-align: center">{$output->countOngoingCommitments()}</td>
    <td style="border: 1px solid #000000; text-align: center">{$output->countFinishedCommitments()}</td>
    <td style="border: 1px solid #000000; text-align: center">{$output->countUnfinishedCommitments()}</td>
    <td style="border: 1px solid #000000; text-align: center">{$output->countAll()}</td>
</tr>
ROW;
}

$html = <<<TABLE
<div class="column-group quarter-gutters" style="color: black;">
    <div class="all-10">
        <img src="assets/img/seal.png" style="width: 80%"/>
    </div>
    <div class="all-90">
        <p style="font-size: 15px; padding-top: 2.5%; font-weight: bold;">Individual Performance Scorecard</p>
    </div>
</div>

<table class="ink-table bordered" style="font-family: sans-serif; margin-top: 10px; color: black;">
    <thead>
        <tr>
            <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="7">Scorecard Report</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000; width: 30%" colspan="1">Name</th>
            <td style="border: 1px solid #000000;" colspan="6">{$user->employee->givenName} {$user->employee->lastName}</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;" colspan="1">Unit</th>
            <td style="border: 1px solid #000000;" colspan="6">{$user->employee->department->name}</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;" colspan="1">Date of Coverage</th>
            <td style="border: 1px solid #000000;" colspan="6">{$input->startingPeriod->format('F d, Y')} to {$input->endingPeriod->format('F d, Y')}</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;" colspan="1">Selected UBT</th>
            <td style="border: 1px solid #000000;" colspan="6">{$user->employee->department->code} - {$input->unitBreakthrough->id}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="7">Summary</th>
        </tr>
        <tr>
            <th style="width: 33%; border: 1px solid #000000;" colspan="1">Status</th>
            <th style="width: 33%; border: 1px solid #000000;" colspan="3">Count</th>
            <th style="width: 33%; border: 1px solid #000000;" colspan="3">Distribution %</th>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;" colspan="1">PENDING</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$pending}</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$pendingPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;" colspan="1">ONGOING</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$ongoing}</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$ongoingPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;" colspan="1">FINISHED</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$finished}</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$finishedPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;" colspan="1">UNFINISHED</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$unfinished}</td>
            <td style="border: 1px solid #000000; text-align: center;" colspan="3">{$unfinishedPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: right; border: 1px solid #000000;" colspan="1">TOTAL COMMITMENTS</td>
            <th style="text-align: left; border: 1px solid #000000;" colspan="6">{$total}</td>
        </tr>
            
        <tr>
            <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="7">Breakdown</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; width: 30%;" rowspan="2">WIG Timeline</th>
            <th style="border: 1px solid #000000; width: 20%;" rowspan="2">Status</th>
            <th style="border: 1px solid #000000; width: 30%;" colspan="4">Commitment Status</th>
            <th style="border: 1px solid #000000; width: 20%;" rowspan="2">Total</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; width: 25%;">Pending</th>
            <th style="border: 1px solid #000000; width: 25%;">Ongoing</th>
            <th style="border: 1px solid #000000; width: 25%;">Finished</th>
            <th style="border: 1px solid #000000; width: 25%;">Unfinished</th>
        </tr>
        {$breakdownHtml}
    </tbody>
</table>
TABLE;

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("{$user->employee->id}_IPREPORT_{$input->startingPeriod->format('Y-m-d')}_{$input->endingPeriod->format('Y-m-d')}.pdf", 'D');

