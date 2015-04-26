<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\Commitment;

ob_end_clean();
error_reporting(0);

$pending = 0;
$ongoing = 0;
$finished = 0;
$unfinished = 0;
$total = count($data);
foreach ($data as $commitment) {
    switch ($commitment->commitmentEnvironmentStatus) {
        case Commitment::STATUS_PENDING:
            $pending++;
            break;
        case Commitment::STATUS_ONGOING:
            $ongoing++;
            break;
        case Commitment::STATUS_FINISHED:
            $finished++;
            break;
        case Commitment::STATUS_UNFINISHED:
            $unfinished++;
            break;
    }
}

$pendingPercentage = number_format((($pending / $total) * 100), 2);
$ongoingPercentage = number_format((($ongoing / $total) * 100), 2);
$finishedPercentage = number_format((($finished / $total) * 100), 2);
$unfinishedPercentage = number_format((($unfinished / $total) * 100), 2);

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage();

$html = <<<TABLE
<div class="column-group quarter-gutters" style="color: black;">
    <div class="all-10">
        <img src="assets/img/seal.png" style="width: 80%"/>
    </div>
    <div class="all-90">
        <p style="font-size: 15px; padding-top: 2.5%; font-weight: bold;">Individual Performance Scorecard</p>
    </div>
</div>

<table class="ink-table bordered" style="font-size: 13px; font-family: sans-serif; margin-top: 10px; color: black;">
    <thead>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;">Name</th>
            <td style="border: 1px solid #000000;" colspan="2">{$user->employee->givenName} {$user->employee->lastName}</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;">Unit</th>
            <td style="border: 1px solid #000000;" colspan="2">{$user->employee->department->name}</td>
        </tr>
        <tr>
            <th style="text-align: left; width: 20%; border: 1px solid #000000;">Date of Coverage</th>
            <td style="border: 1px solid #000000;" colspan="2">{$input->startingPeriod->format('F d, Y')} to {$input->endingPeriod->format('F d, Y')}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="3">Scorecard Report</th>
        </tr>
        <tr>
            <th style="width: 33%; border: 1px solid #000000;">Status</th>
            <th style="width: 33%; border: 1px solid #000000;">Count</th>
            <th style="width: 33%; border: 1px solid #000000;">Distribution %</th>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;">PENDING</td>
            <td style="border: 1px solid #000000;">{$pending}</td>
            <td style="border: 1px solid #000000;">{$pendingPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;">ONGOING</td>
            <td style="border: 1px solid #000000;">{$ongoing}</td>
            <td style="border: 1px solid #000000;">{$ongoingPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;">FINISHED</td>
            <td style="border: 1px solid #000000;">{$finished}</td>
            <td style="border: 1px solid #000000;">{$finishedPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: left; border: 1px solid #000000;">UNFINISHED</td>
            <td style="border: 1px solid #000000;">{$unfinished}</td>
            <td style="border: 1px solid #000000;">{$unfinishedPercentage}</td>
        </tr>
        <tr>
            <th style="text-align: right; border: 1px solid #000000;" colspan="2">TOTAL COMMITMENTS</td>
            <th style="text-align: left; border: 1px solid #000000;">{$total}</td>
        </tr>
    </tbody>
</table>
TABLE;

$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("{$user->employee->id}_IPREPORT_{$input->startingPeriod->format('Y-m-d')}_{$input->endingPeriod->format('Y-m-d')}.pdf", 'D');

