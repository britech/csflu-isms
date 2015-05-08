<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$headerTable = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black;">
    <tbody>
        <tr>
            <td style="font-weight: bold; width: 10%; border: 1px solid #000000;">Unit</td>
            <td style="border: 1px solid #000000;">{$ubtData->unit->name}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; width: 10%; border: 1px solid #000000;">Venue</td>
            <td style="border: 1px solid #000000;">{$wigData->wigMeeting->meetingVenue}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; width: 10%; border: 1px solid #000000;">Time</td>
            <td style="border: 1px solid #000000;">{$wigData->wigMeeting->meetingTimeStart->format('g:i A')} - {$wigData->wigMeeting->meetingTimeEnd->format('g:i A')} ({$timeDifference->format('%h hrs %i mins')})</td>
        </tr>
    </tbody>
</table>
TABLE;


$footerTable = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; margin-top: 10px; color: black;">
    <tbody>
        <tr>
            <td style="font-weight: bold; width: 20%; border: 1px solid #000000;">Scoreboard Update</td>
            <td style="border: 1px solid #000000;">
                Unit Breakthrough:&nbsp;{$ubtFigure} {$ubtData->uom->getAppropriateUomDisplay()}<br/>
                Lead Measure 1:&nbsp;{$lm1Figure} {$lm1Data->uom->getAppropriateUomDisplay()}<br/>
                Lead Measure 2:&nbsp;{$lm2Figure} {$lm2Data->uom->getAppropriateUomDisplay()}<br/>
            </td>
        </tr>
    </tbody>
</table>
TABLE;

$html = "{$headerTable} {$footerTable}";
$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("WIG-REPORT_{$wigData->startingPeriod->format('Y-m-d')}_{$wigData->endingPeriod->format('Y-m-d')}.pdf", 'D');
