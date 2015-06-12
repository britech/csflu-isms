<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;

ob_end_clean();
error_reporting(0);

$titleHtml = <<<TITLE
<div class="column-group quarter-gutters" style="color: black;">
    <div class="all-10">
        <img src="assets/img/seal.png" style="width: 80%"/>
    </div>
    <div class="all-90">
        <p style="font-size: 15px; padding-top: 2.5%; font-weight: bold;">
            {$strategyMap->name}
            <br/>
            {$strategyMap->visionStatement}
        </p>
    </div>
</div> 
TITLE;

$measureHtml = <<<MEASURE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; margin-top: 10px; font-size: 12px;">
    <tbody>
        <tr>
            <th style="border: 1px solid #000000; width: 50%">INDICATOR</th>
            <th style="border: 1px solid #000000; width: 25%">MOVEMENT AS OF <br/>{$period->format('F Y')}</th>
            <th style="border: 1px solid #000000; width: 25%">REMARKS</th>
        </tr>
        <tr>
            <td style="border: 1px solid #000000; text-align: center; font-size: 15px;">{$measureProfile->indicator->description}</td>
            <td style="border: 1px solid #000000; text-align: center; font-weight: bold; font-size: 20px;">{$measureProfile->resolveLatestMovementValue($period)}</td>
            <td style="border: 1px solid #000000;">{$measureProfile->resolveLatestMovementRemarks($period)}</td>
        </tr>    
    </tbody>
</table>
MEASURE;

$initiativeBody = "";
if (count($initiatives) == 0) {
    $initiativeBody = <<<INITIATIVE_BODY
<tr>
   <td style="border: 1px solid #000000;" colspan="3">No Initiatives Aligned</td>
</tr>
INITIATIVE_BODY;
} else {
    foreach ($initiatives as $initiative) {
        if (empty($initiative->countActivities())) {
            $initiativeBody.=<<<INITIATIVE_BODY
<tr>
   <td style="border: 1px solid #000000;">{$initiative->title}</td>
   <td style="border: 1px solid #000000;" colspan="2">No Activities Defined</td>
</tr>
INITIATIVE_BODY;
        } else {
            $initiativeBody.=<<<INITIATIVE_BODY
<tr>
   <td style="border: 1px solid #000000;">{$initiative->title}</td>
   <td style="border: 1px solid #000000;">{$initiative->resolveTotalAccomplishmentRate($period)}</td>
   <td style="border: 1px solid #000000;">{$initiative->resolveTotalBudgetBurnRate($period)}</td>
</tr>
INITIATIVE_BODY;
        }
    }
}

$initiativeHtml = <<<INITIATIVE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; margin-top: 10px; font-size: 12px;">
    <thead>
        <tr>
            <td style="border: 1px solid #000000; width: 33%; font-weight: bold;">Initiative</td>
            <td style="border: 1px solid #000000; width: 33%; font-weight: bold;">Accomplishment Rate</td>
            <td style="border: 1px solid #000000; width: 33%; font-weight: bold;">Budget Burn Rate</td>
        </tr>    
    </thead>
    <tbody>
        {$initiativeBody}
    </tbody>
</table>
INITIATIVE;

$ubtBody = "";
if (count($unitBreakthroughs) == 0) {
    $ubtBody = <<<UBT_BODY
<tr>
   <td style="border: 1px solid #000000;" colspan="4">No Unit Breakthroughs Aligned</td>
</tr>
UBT_BODY;
} else {
    foreach ($unitBreakthroughs as $unitBreakthrough) {
        $leadMeasures = $unitBreakthrough->filterLeadMeasures($period);
        $ubtBody.= <<<UBT_BODY
<tr>
   <td style="border: 1px solid #000000;" rowspan="3">{$unitBreakthrough->unit->name}</td>
   <td style="border: 1px solid #000000; width: 10%; font-size: 10px;">Unit Breakthrough</td>
   <td style="border: 1px solid #000000;">{$unitBreakthrough->description}</td>
   <td style="border: 1px solid #000000;">{$unitBreakthrough->resolveUnitBreakthroughMovement($period)}</td>
</tr>

<tr>
   <td style="border: 1px solid #000000; width: 10%; font-size: 10px;">Lead Measure 1</td>
   <td style="border: 1px solid #000000;">{$leadMeasures[0]->description}</td>
   <td style="border: 1px solid #000000;">{$unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_1)}</td>
</tr>

<tr>
   <td style="border: 1px solid #000000; width: 10%; font-size: 10px;">Lead Measure 2</td>
   <td style="border: 1px solid #000000;">{$leadMeasures[1]->description}</td>
   <td style="border: 1px solid #000000;">{$unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_2)}</td>
</tr>
UBT_BODY;
    }
}

$ubtHtml = <<<UBT
<table class="ink-table bordered" style="font-family: sans-serif; color: black; margin-top: 10px; font-size: 12px;">
    <thead>
        <tr>
            <td style="border: 1px solid #000000; width: 25%; font-weight: bold;">Unit</td>
            <td style="border: 1px solid #000000; width: 50%; font-weight: bold;" colspan="2">Unit Breakthrough and Lead Measures</td>
            <td style="border: 1px solid #000000; width: 25%; font-weight: bold;">Movement Value</td>
        </tr>    
    </thead>
    <tbody>
        {$ubtBody}
    </tbody>
</table>
UBT;

$html = "{$titleHtml}{$measureHtml}{$initiativeHtml}{$ubtHtml}";

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("RPT_SCORECARD_UPD_{$measureProfile->indicator->description}_{$period->format('F Y')}.pdf", 'D');

