<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$titleHtml = <<<TITLE
<div class="column-group quarter-gutters" style="color: black;">
    <div class="all-10">
        <img src="assets/img/seal.png" style="width: 80%"/>
    </div>
    <div class="all-90">
        <p style="font-size: 15px; padding-top: 2.5%; font-weight: bold;">
            CITY OF SAN FERNANDO, LA UNION
            <br/>
            Program of Work
        </p>
    </div>
</div> 
TITLE;

$headerHtml = <<<HEADER
<tr>
    <th style="border: 1px solid #000000; background-color: #0070C0; color: white;" colspan="9">FIRST LEVEL STRATEGIC INITIATIVES</th>
</tr>
<tr>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="2">INITIATIVE</th>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="2">DESCRIPTION</th>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="3">KEY MEASURE/S ADDRESSED</th>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="2">PROJECT TEAM</th>
</tr>
<tr>
    <th style="border: 1px solid #000000;" colspan="2" rowspan="2">{$initiative->title}</th>
    <td style="border: 1px solid #000000;" colspan="2" rowspan="2">{$initiative->description}</td>
    <td style="border: 1px solid #000000;" colspan="3" rowspan="2">{$measures}</td>
    <th style="border: 1px solid #000000; background-color: #C5D9F1;" colspan="2">OWNER/S</th>
</tr>
<tr>
    <td style="border: 1px solid #000000;" colspan="2">{$teams}</td>
</tr>

<tr>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="7">PROJECT OBJECTIVE/S</th>
    <th style="border: 1px solid #000000; background-color: #C5D9F1;" colspan="2">ADVISER/S</th>
</tr>
<tr>
    <td style="border: 1px solid #000000;" colspan="7">{$objectives}</td>
    <td style="border: 1px solid #000000;" colspan="2">{$advisers}</td>
</tr>
    
<tr>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="4">BENEFICIARIES</th>
    <th style="border: 1px solid #000000; background-color: #C4D79B;" colspan="3">TIMELINE</th>
    <th style="border: 1px solid #000000; background-color: #C5D9F1;" colspan="2">EO NUMBER</th>
</tr>
<tr>
    <td style="border: 1px solid #000000;" colspan="4" rowspan="2">{$beneficiaries}</td>
    <th style="border: 1px solid #000000; background-color: #C5D9F1;" colspan="1">START</th>
    <td style="border: 1px solid #000000;" colspan="2">{$initiative->startingPeriod->format('F Y')}</td>
    <td style="border: 1px solid #000000; text-align: center;" colspan="2" rowspan="2">{$initiative->eoNumber}</td>
</tr>
<tr>
    <th style="border: 1px solid #000000; background-color: #C5D9F1;" colspan="1">END</th>
    <td style="border: 1px solid #000000;" colspan="2">{$initiative->endingPeriod->format('F Y')}</td>
</tr>
    
<tr>
    <th style="border: 1px solid #000000; background-color: #0070C0; color: white;" colspan="9">PROJECT MILESTONES AND BUDGET</th>
</tr>
HEADER;

$bodyHtml = "";
foreach ($initiative->phases as $phase) {
    $bodyHtml.=<<<PHASE
<tr>
    <th style="border: 1px solid #000000; background-color: #FFFF99;" colspan="9">PHASE {$phase->phaseNumber}: {$phase->title}</th>
</tr>
<tr>
    <td style="border: 1px solid #000000; text-align: center;" colspan="9">{$phase->description}</td>
</tr>
    
<tr>
    <th style="border: 1px solid #000000; width: 5%; background-color: #C5D9F1;">#</th>
    <th style="border: 1px solid #000000; width: 15%; background-color: #C5D9F1;">TARGET DATE</th>
    <th style="border: 1px solid #000000; width: 15%; background-color: #C5D9F1;">COMPONENT</th>
    <th style="border: 1px solid #000000; width: 15%; background-color: #C5D9F1;">ACTIVITY</th>
    <th style="border: 1px solid #000000; width: 10%; background-color: #C5D9F1;">TARGET</th>
    <th style="border: 1px solid #000000; width: 10%; background-color: #C5D9F1;">INDICATOR</th>
    <th style="border: 1px solid #000000; width: 10%; background-color: #C5D9F1;">BUDGET</th>
    <th style="border: 1px solid #000000; width: 10%; background-color: #C5D9F1;">SOURCE</th>
    <th style="border: 1px solid #000000; width: 10%; background-color: #C5D9F1;">OWNER</th>
</tr>
PHASE;

    foreach ($phase->components as $component) {
        $activityCount = count($component->activities);
        if ($activityCount > 0) {
            $bodyHtml.=<<<ACTIVITY
<tr>
    <td style="border: 1px solid #000000; text-align: center;" rowspan="{$activityCount}">{$component->activities[0]->activityNumber}</td>
    <td style="border: 1px solid #000000;" rowspan="{$activityCount}">{$component->activities[0]->startingPeriod->format('M Y')} - {$component->activities[0]->endingPeriod->format('M Y')}</td>
    <td style="border: 1px solid #000000;" rowspan="{$activityCount}">{$component->description}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->title}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->descriptionOfTarget}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->indicator}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->resolveBudgetFigure()}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->resolveBudgetSource()}</td>
    <td style="border: 1px solid #000000;">{$component->activities[0]->resolveOwners()}</td>
</tr>
ACTIVITY;

            for ($i = 1; $i < $activityCount; $i++) {
                $bodyHtml.=<<<ACTIVITY
<tr>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->title}</td>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->descriptionOfTarget}</td>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->indicator}</td>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->resolveBudgetFigure()}</td>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->resolveBudgetSource()}</td>
    <td style="border: 1px solid #000000;">{$component->activities[$i]->resolveOwners()}</td>
</tr>
ACTIVITY;
            }
        }
    }
    $bodyHtml.=<<<COMPONENT
<tr>
    <th style="border: 1px solid #000000;" colspan="6">TOTAL</th>
    <th style="border: 1px solid #000000;" colspan="1">{$component->resolveTotalBudgetAllocation()}</th>
    <th style="border: 1px solid #000000;" colspan="1">&nbsp;</th>
    <th style="border: 1px solid #000000;" colspan="1">&nbsp;</th>
</tr>
COMPONENT;
}

$html = <<<HTML
{$titleHtml}
<table class="ink-table bordered" style="font-family: sans-serif; color: black; font-size: 10px;">
    <tbody>
        {$headerHtml}
        {$bodyHtml}
    <tr>
        <th style="border: 1px solid #000000;" colspan="6">GRAND TOTAL</th>
        <th style="border: 1px solid #000000;" colspan="1">{$initiative->resolveTotalBudgetAllocation()}</th>
        <th style="border: 1px solid #000000;" colspan="1">&nbsp;</th>
        <th style="border: 1px solid #000000;" colspan="1">&nbsp;</th>
    </tr>
    </tbody>
</table>
HTML;

$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("RPT_INITIATIVE_POW_{$initiative->title}.pdf", 'D');
