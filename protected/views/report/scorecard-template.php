<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\MeasureProfile;

ob_end_clean();
//error_reporting(0);

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

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;

$yearHeader = "";
$baselineWidth = $baselineCount / 20;
$targetsWidth = $targetsCount / 25;
foreach ($baselineYears as $baselineYear) {
    $yearHeader .=<<<HEADER
<th style="border: 1px solid #000000; font-size: 9px;">{$baselineYear}</th>
HEADER;
}

foreach ($targetYears as $targetYear) {
    $yearHeader .=<<<HEADER
<th style="border: 1px solid #000000; font-size: 9px;">{$targetYear}</th>
HEADER;
}

$totalRowCount = $baselineCount + $targetsCount + 6;

foreach ($perspectives as $perspective) {
    $row = "";
    foreach ($strategyMap->objectives as $objective) {
        if ($objective->perspective == $perspective) {
            $measureProfiles = $alignmentService->listAlignedMeasureProfiles($strategyMap, $objective);
            $rowspan = count($measureProfiles);
            if ($rowspan > 0) {

                if ($measureProfiles[0]->measureType == MeasureProfile::TYPE_LEAD) {
                    $firstMeasureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000; background-color: #C5D9F1;">&nbsp;</td>
<td style="border: 1px solid #000000;">&nbsp;</td>
MEASURE_TYPE;
                } elseif ($measureProfiles[0]->measureType == MeasureProfile::TYPE_LAG) {
                    $firstMeasureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000;">&nbsp;</td>
<td style="border: 1px solid #000000; background-color: #C5D9F1;">&nbsp;</td>
MEASURE_TYPE;
                } else {
                    $firstMeasureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000;">&nbsp;</td>
<td style="border: 1px solid #000000;">&nbsp;</td>
MEASURE_TYPE;
                }

                $firstValueData = "";
                foreach ($baselineYears as $baselineYear) {
                    $firstValueData .= <<<VALUE_DATA
<td style="border: 1px solid #000000;">{$measureProfiles[0]->indicator->resolveBaselineValue($baselineYear)}</td>
VALUE_DATA;
                }

                foreach ($targetYears as $targetYear) {
                    $firstValueData .= <<<VALUE_DATA
<td style="border: 1px solid #000000;">{$measureProfiles[0]->resolveTargetValue($targetYear)}</td>
VALUE_DATA;
                }

                $row .= <<<ROW
<tr>
    <td style="border: 1px solid #000000;" rowspan="{$rowspan}">{$objective->description}</td>
    <td style="border: 1px solid #000000;">{$measureProfiles[0]->indicator->description}</td>
    <td style="border: 1px solid #000000;">{$measureProfiles[0]->indicator->uom->description}</td>
    {$firstMeasureType}
    <td style="border: 1px solid #000000;">{$measureProfiles[0]->resolveLeadOffices(LeadOffice::RESPONSIBILITY_ACCOUNTABLE, true)}</td>
    {$firstValueData}
</tr>             
ROW;
                for ($i = 1; $i < count($measureProfiles); $i++) {
                    if ($measureProfiles[$i]->measureType == MeasureProfile::TYPE_LEAD) {
                        $measureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000; background-color: #C5D9F1;">&nbsp;</td>
<td style="border: 1px solid #000000;">&nbsp;</td>
MEASURE_TYPE;
                    } elseif ($measureProfiles[$i]->measureType == MeasureProfile::TYPE_LAG) {
                        $measureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000;">&nbsp;</td>
<td style="border: 1px solid #000000; background-color: #C5D9F1;">&nbsp;</td>
MEASURE_TYPE;
                    } else {
                        $measureType = <<<MEASURE_TYPE
<td style="border: 1px solid #000000;">&nbsp;</td>
<td style="border: 1px solid #000000;">&nbsp;</td>
MEASURE_TYPE;
                    }

                    $valueData = "";
                    foreach ($baselineYears as $baselineYear) {
                        $valueData .= <<<VALUE_DATA
<td style="border: 1px solid #000000;">{$measureProfiles[$i]->indicator->resolveBaselineValue($baselineYear)}</td>
VALUE_DATA;
                    }

                    foreach ($targetYears as $targetYear) {
                        $valueData .= <<<VALUE_DATA
<td style="border: 1px solid #000000;">{$measureProfiles[$i]->resolveTargetValue($targetYear)}</td>
VALUE_DATA;
                    }
                    $row .= <<<ROW
<tr>
    <td style="border: 1px solid #000000;">{$measureProfiles[$i]->indicator->description}</td>
    <td style="border: 1px solid #000000;">{$measureProfiles[$i]->indicator->uom->description}</td>
    {$measureType}
    <td style="border: 1px solid #000000;">{$measureProfiles[$i]->resolveLeadOffices(LeadOffice::RESPONSIBILITY_ACCOUNTABLE, true)}</td>
    {$valueData}
</tr>
ROW;
                }
            }
        }
    }

    $table = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black; margin-top: 10px; font-size: 10px;">
    <thead>
        <tr>
            <th style="border: 1px solid #000000; width: 15%" rowspan="2">STRATEGIC OBJECTIVE</th>
            <th style="border: 1px solid #000000; width: 15%" rowspan="2">PERFORMANCE INDICATOR</th>
            <th style="border: 1px solid #000000; width: 10%" rowspan="2">UNIT OF MEASURE</th>
            <th style="border: 1px solid #000000; width: 5%;" colspan="2">TYPE</th>
            <th style="border: 1px solid #000000; width: 10%" rowspan="2">LEAD OFFICE</th>
            <th style="border: 1px solid #000000; width: 20%" colspan="{$baselineCount}">BASELINE</th>
            <th style="border: 1px solid #000000; width: 25%" colspan="{$targetsCount}">TARGETS</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; font-size: 9px;">LD</th>
            <th style="border: 1px solid #000000; font-size: 9px;">LG</th>
            {$yearHeader}
        </tr>
        <tr>
            <th style="border: 1px solid #000000; text-transform: uppercase; text-align: left;" colspan="{$totalRowCount}">Perspective:&nbsp;{$perspective->description}</th>
        </tr>
    </thead>
    <tbody>
        {$row}
    </tbody>
</table>
TABLE;


    $html = "{$titleHtml}{$table}";
    $pdf->AddPage('L');
    $css = file_get_contents('assets/ink/css/ink.css');
    $pdf->writeHTML($css, 1);
    $pdf->writeHTML($html);
}

$pdf->Output("RPT_SCORECARD_TEMPLATE_{$strategyMap->name}.pdf", 'D');

