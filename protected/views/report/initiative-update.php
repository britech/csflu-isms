<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$headerHtml = <<<HEADER
<tr>
    <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">FIRST LEVEL STRATEGIC INITIATIVES</th>
</tr>
<tr>
    <th style="border: 1px solid #000000;" colspan="4">PROGRESS FOR THE MONTH OF:</th>
    <td style="border: 1px solid #000000;" colspan="5">{$period->format('F Y')}</td>
</tr>
<tr>
    <th style="border: 1px solid #000000;" colspan="4">INITIATIVE NAME</th>
    <th style="border: 1px solid #000000;" colspan="3">DESCRIPTION/INNOVATIVE COMPONENT</th>
    <th style="border: 1px solid #000000;" colspan="2">INITIATIVE TEAM</th>
</tr>
<tr>
    <td style="border: 1px solid #000000; text-align: center;" colspan="4">{$initiative->title}</td>
    <td style="border: 1px solid #000000;" colspan="3">{$initiative->description}</td>
    <td style="border: 1px solid #000000;" colspan="2">{$teams}</td>
</tr>

<tr>
    <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">PROJECT MANAGEMENT COMPONENT</th>
</tr>
<tr>
    <th style="border: 1px solid #000000;" colspan="1">STATUS</th>
    <th style="border: 1px solid #000000;" colspan="2">APPROVED CONCEPT</th>
    <th style="border: 1px solid #000000;" colspan="1">ADOPTED IN AIP</th>
    <th style="border: 1px solid #000000;" colspan="1">OWNER IS INFORMED</th>
    <th style="border: 1px solid #000000;" colspan="1">ORGANIZED PROJECT TEAM</th>
    <th style="border: 1px solid #000000;" colspan="1">APPROVED BUDGET</th>
    <th style="border: 1px solid #000000;" colspan="2">REPORTING MECHANISM</th>
</tr>
<tr>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">{$initiative->translateStatusCode()}</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="2">YES</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">YES</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">YES</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">YES</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">YES</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="2">MONTHLY</td>
</tr>

<tr>
    <th style="border: 1px solid #000000;" colspan="4">BENEFICIARIES</th>
    <th style="border: 1px solid #000000;" colspan="1">TARGET</th>
    <th style="border: 1px solid #000000;" colspan="1">REALIZED</th>
    <th style="border: 1px solid #000000;" colspan="3">REMARKS</th>
</tr>
<tr>
    <td style="border: 1px solid #000000;" colspan="4">{$beneficiaries}</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">-</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="1">-</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="3">-</td>
</tr>

<tr>
    <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="9">PROJECT MILESTONES AND BUDGET</th>
</tr>
HEADER;

$content = "";
foreach ($initiative->phases as $phase) {
    $phaseHeader = <<<PHASE
<tr>
    <th style="border: 1px solid #000000;" colspan="9">PHASE {$phase->phaseNumber}: {$phase->title}</th>
</tr>
<tr>
    <td style="border: 1px solid #000000;" colspan="9">{$phase->description}</td>
</tr>
PHASE;

    $contentHeader = <<<HEADER
<tr>
    <th style="border: 1px solid #000000; width: 11%;">TARGET DATE</th>
    <th style="border: 1px solid #000000; width: 11%;">COMPONENT</th>
    <th style="border: 1px solid #000000; width: 11%;">ACTIVITY</th>
    <th style="border: 1px solid #000000; width: 11%;">TARGET</th>
    <th style="border: 1px solid #000000; width: 11%;">ACTUAL</th>
    <th style="border: 1px solid #000000; width: 11%;">PERCENT</th>
    <th style="border: 1px solid #000000; width: 11%;">BUDGET</th>
    <th style="border: 1px solid #000000; width: 11%;">BUDGET UTILIZED</th>
    <th style="border: 1px solid #000000; width: 11%;">REMARKS</th>
</tr>
HEADER;

    $contentBody = "";
    foreach ($phase->components as $component) {
        foreach ($component->activities as $activity) {
            $contentBody.=<<<BODY
<tr>
    <td style="border: 1px solid #000000;">{$activity->endingPeriod->format('M Y')}</td>
    <td style="border: 1px solid #000000;">{$component->description}</td>
    <td style="border: 1px solid #000000;">{$activity->title}</td>
    <td style="border: 1px solid #000000;">{$activity->descriptionOfTarget}</td>
    <td style="border: 1px solid #000000;">{$activity->resolveActualFigure($period)}</td>
    <td style="border: 1px solid #000000; text-align: center;">{$activity->resolveCompletionPercentage($period)}</td>
    <td style="border: 1px solid #000000; text-align: center;">{$activity->resolveBudgetFigure()}</td>
    <td style="border: 1px solid #000000; text-align: center;">{$activity->resolveBudgetUtilization($period)}</td>
    <td style="border: 1px solid #000000; text-align: center;">-</td>
</tr>
BODY;
        }
    }

    $content.= "{$phaseHeader}{$contentHeader}{$contentBody}";
}

$footerHtml = <<<FOOTER
<tr>
    <th style="border: 1px solid #000000;" colspan="5">ACCOMPLISHMENT RATE</th>
    <th style="border: 1px solid #000000;" colspan="4">{$initiative->resolveAccomplishmentRate($period)}</th>
</tr>
<tr>
    <th style="border: 1px solid #000000;" colspan="5">BUDGET BURN RATE</th>
    <th style="border: 1px solid #000000;" colspan="4">{$initiative->resolveBudgetBurnRate($period)}</th>
</tr>
FOOTER;

$html = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; font-size: 10px;">
    <tbody>
        {$headerHtml}
        {$content}
        {$footerHtml}
    </tbody>
</table>
TABLE;

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$logBody = "";
foreach ($initiative->phases as $phase) {
    foreach ($phase->components as $component) {
        foreach ($component->activities as $activity) {
            $logBody.=<<<HEADER
    <tr>
        <th style="border: 1px solid #000000; text-align: left;" colspan="5">Activity: {$activity->title}</th>
    </tr>
HEADER;
            foreach ($activity->movements as $movement) {
                if ($movement->periodDate == $period || ($period >= $activity->startingPeriod && $period <= $activity->endingPeriod)) {
                    $logBody.=<<<BODY
    <tr>
        <td style="border: 1px solid #000000;">{$movement->movementTimestamp->format('m/d/Y H:i:s')}</td>
        <td style="border: 1px solid #000000;">{$movement->retrieveName()}</td>
        <td style="border: 1px solid #000000;">{$movement->resolveOutputValue()}</td>
        <td style="border: 1px solid #000000;">{$movement->resolveBudgetValue()}</td>
        <td style="border: 1px solid #000000;">{$movement->constructNotes()}</td>
    </tr>
BODY;
                }
            }
        }
    }
}

$logHtml = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; font-size: 10px;">
   <thead>
    <tr>
        <th style="border: 1px solid #000000; background-color: black; color: white;" colspan="5">MOVEMENT LOG</th>
    </tr>
    <tr>
        <th style="border: 1px solid #000000; width: 20%;">Timestamp</th>
        <th style="border: 1px solid #000000; width: 20%;">Entered By</th>
        <th style="border: 1px solid #000000; width: 20%;">Output</th>
        <th style="border: 1px solid #000000; width: 20%;">Spent Amount</th>
        <th style="border: 1px solid #000000; width: 20%;">Notes</th>
    </tr>
   </thead> 
   <tbody>
        {$logBody}
   </tbody>
</table>
TABLE;


$pdf->mirrorMargins = .5;
$pdf->AddPage('P');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($logHtml);

$pdf->Output("RPT_INITIATIVE_UPDATE_{$initiative->id}_{$period->format('F_Y')}.pdf", 'D');
