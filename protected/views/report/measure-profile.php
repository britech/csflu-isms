<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\indicator\Indicator;

$titleHtml = <<<TITLE
<div class="all-100" style="color: yellow; background-color: blue; text-align:center; font-size: 20px; text-outline: yellow 10px;">Measure Profile</div>
TITLE;

$row1Data = <<<ROW1
<div class="all-70" style="border: 1px solid black; font-size: 12px; margin-top: 10px; padding: 10px; border-radius: 20px; height: 25%;">
    <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">What is the objective?</h6>
    <span style="color: black;">{$measureProfile->objective->description}</span>
    
    <h6 style="color: black; margin-top: 10px; margin-bottom: 0px; font-weight: bold;">What is the measure?</h6>
    <span style="color: black;">{$measureProfile->indicator->description}</span>
    
    <h6 style="color: black; margin-top: 10px; margin-bottom: 0px; font-weight: bold;">What is the reason behind choosing this measure?</h6>
    <span style="color: black; font-size: 10px;">{$measureProfile->indicator->rationale}</span>
</div>

<div class="all-25" style="border: 1px solid black; font-size: 12px; margin-left: 10px; padding: 10px; border-radius: 20px; height: 25%;">
    <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">How often is the measure updated/calculated?</h6>
    <span style="color: black;">{$measureProfile->translateFrequencyTypeCode()}</span>
    
    <h6 style="color: black; margin-top: 10px; margin-bottom: 0px; font-weight: bold;">What is the unit of measure used?</h6>
    <span style="color: black;">{$measureProfile->indicator->uom->description}</span>
</div>
ROW1;

$row2Data = <<<ROW2
<div class="all-50" style="border: 1px solid black; font-size: 12px; margin-top: 10px; padding: 10px; border-radius: 20px; height: 12%;">
    <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">How is the measure calculated? Clarify the terms in the formula</h6>
    <span style="color: black; font-size: 10px;">{$measureProfile->indicator->formula}</span>
</div>
<div class="all-45" style="border: 1px solid black; font-size: 12px; margin-left: 10px; padding: 10px; border-radius: 20px; height: 12%;">
    <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">What data is required in calculating the measure?<br/>Where/how is it acquired</h6>
    <span style="color: black; font-size: 10px;">{$dataSource}</span>
</div>
ROW2;

$dataSourceStatus = "";

switch ($measureProfile->indicator->dataSourceStatus) {
    case Indicator::STAT_AVAILABLE:
        $dataSourceStatus = <<<STATUS
<p style="color: black; margin-bottom: 0px; font-weight: bold; font-style: underline;">[X] Currently available</p>
<p style="color: black; margin-bottom: 0px;">[] With Minor Changes</p>
<p style="color: black; margin-bottom: 0px;">[] Still to be formulated</p>
STATUS;
        break;
    case Indicator::STAT_FORMULATED:
        $dataSourceStatus = <<<STATUS
<p style="color: black; margin-bottom: 0px;">[] Currently available</p>
<p style="color: black; margin-bottom: 0px;">[] With Minor Changes</p>
<p style="color: black; margin-bottom: 0px; font-weight: bold; font-style: underline;">[X] Still to be formulated</p>
STATUS;
        break;
    case Indicator::STAT_MINOR_CHANGE:
        $dataSourceStatus = <<<STATUS
<p style="color: black; margin-bottom: 0px;">[] Currently available</p>
<p style="color: black; margin-bottom: 0px; font-weight: bold; font-style: underline;">[X] With Minor Changes</p>
<p style="color: black; margin-bottom: 0px;">[] Still to be formulated</p>
STATUS;
        break;
}

$baselineHeader = "";
foreach($baselineYears as $baselineYear){
    $baselineHeader .= <<<BASELINE_HEADER
<th style="border: 1px solid #000000;">{$baselineYear}</th>
BASELINE_HEADER;
}

$targetsHeader = "";
foreach($targetYears as $targetYear){
    $targetsHeader .= <<<TARGET_HEADER
<th style="border: 1px solid #000000;">{$targetYear}</th>
TARGET_HEADER;
}

$dataContainer = "";
foreach($dataGroups as $dataGroup){
    $dataBody = "";
    foreach($measureProfile->indicator->baselineData as $baseline){
        if($baseline->dataGroup == $dataGroup){
            $dataBody .= <<<BODY
<td style="border: 1px solid #000000;">{$baseline->value}</td>
BODY;
        }
    }
    
    foreach($measureProfile->targets as $target){
        if($target->dataGroup == $dataGroup){
            $dataBody.= <<<BODY
<td style="border: 1px solid #000000;">{$target->value}</td>
BODY;
        }
    }
    
    $dataContainer .= <<<CONTAINER
<tr>
    <td style="border: 1px solid #000000;">{$dataGroup}</td>
    {$dataBody}
</tr>
CONTAINER;
}

$row3Data = <<<ROW3
<div class="column-group quarter-gutters">
    <div class="all-50">
        <div class="column-group quarter-gutters">
            <div class="all-50" style="border: 1px solid black; font-size: 12px; margin-top: 10px; padding: 10px; border-radius: 20px; height: 6%;">
                <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">Is the information about the measure available?</h6>
                <span style="color: black;">{$dataSourceStatus}</span>
            </div>
            <div class="all-40" style="border: 1px solid black; font-size: 12px; margin-left: 10px; padding: 10px; border-radius: 20px; height: 13%;">
                <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">When will this info be available?</h6>
                <span style="color: black;">{$measureProfile->indicator->dataSourceAvailabilityDate}</span>
            </div>
        </div>
        <div class="all-100" style="border: 1px solid black; font-size: 12px; padding: 10px; border-radius: 20px; margin-top: 10px; height: 30%;">
            <h6 style="color: black; margin-bottom: 0px; font-weight: bold;">Who is responsible for setting targets?</h6>
            <span style="color: black;">{$setters}</span>
            
            <h6 style="color: black; margin-top:10px; margin-bottom: 0px; font-weight: bold;">Who is accountable for targets?</h6>
            <span style="color: black;">{$owners}</span>
            
            <h6 style="color: black; margin-top:10px; margin-bottom: 0px; font-weight: bold;">Who is responsible for tracking and reporting targets?</h6>
            <span style="color: black;">{$trackers}</span>
        </div>
    </div>
    <div class="all-50">
        <div style="border: 1px solid black; font-size: 12px; margin-top: 10px; margin-left: 10px; padding: 10px; border-radius: 20px; height: 45%;">
            <table class="ink-table bordered" style="font-family: sans-serif; color: black; font-size: 12px;">
                <tbody>
                    <tr>
                        <th style="border: 1px solid #000000; width: 20%;" rowspan="2">&nbsp;</th>
                        <th style="border: 1px solid #000000; width: 50%" colspan="{$baselineCount}">Baseline</th>
                        <th style="border: 1px solid #000000; width: 50%" colspan="{$targetsCount}">Targets</th>
                    </tr>
                    <tr>
                        {$baselineHeader}
                        {$targetsHeader}
                    </tr>
                    {$dataContainer}
                </tbody>
            </table>
        </div>
    </div>
</div>
ROW3;

$html = <<<PDF_BODY
<div class="column-group quarter-gutters">
    {$titleHtml}
    {$row1Data}
    {$row2Data}
    {$row3Data}
</div>
PDF_BODY;

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("RPT_MEASURE_PROFILE_{$measureProfile->indicator->description}.pdf", 'D');

