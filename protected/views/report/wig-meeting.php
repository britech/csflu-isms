<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;

ob_end_clean();
error_reporting(0);

$tbody = "";
foreach ($accounts as $account) {
    $firstRow = "";
    $otherRow = "";
    $count = $collatedCommitments[$account->id]->countAll();
    $rowCount = $count == 0 ? 1 : $count;
    if ($count > 0) {
        foreach ($wigData->commitments as $commitment) {
            if ($commitment->user->id == $account->id) {
                $firstCommitmentEntry = $commitment->commitment;
                $firstCommitmentMovementEntry = "";
                if (count($commitment->commitmentMovements) == 0) {
                    $firstCommitmentMovementEntry = "N/A";
                } else {
                    $entry = "";
                    foreach ($commitment->commitmentMovements as $movement) {
                        $entry.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
                    }
                    $firstCommitmentMovementEntry = nl2br($entry);
                }
                $id = $commitment->id;
                break;
            }
        }


        $firstRow.= <<<ROW
    <tr>
        <td style="border: 1px solid #000000;" rowspan="{$rowCount}">{$account->employee->givenName} {$account->employee->lastName}</td>
        <td style="border: 1px solid #000000;">{$firstCommitmentEntry}</td>
        <td style="border: 1px solid #000000;">{$firstCommitmentMovementEntry}</td>
    </tr>
ROW;
        foreach ($wigData->commitments as $data) {
            if ($data->id != $id && $data->user->id == $account->id) {
                $movementEntry = "";
                if (count($data->commitmentMovements) == 0) {
                    $movementEntry = "N/A";
                } else {
                    $entry = "";
                    foreach ($data->commitmentMovements as $movement) {
                        $entry.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
                    }
                    $movementEntry = nl2br($entry);
                }
                $otherRow.=<<<ROW
    <tr>
        <td  style="border: 1px solid #000000;">{$data->commitment}</td>
        <td  style="border: 1px solid #000000;">{$movementEntry}</td>
    </tr>
ROW;
            }
        }
    } else {
        $firstRow.=<<<ROW
    <tr>
        <td style="border: 1px solid #000000;">{$account->employee->givenName} {$account->employee->lastName}</td>
        <td style="border: 1px solid #000000;" colspan="2">No Commitments defined</td>
    </tr>
ROW;
    }
    $tbody.=$firstRow . $otherRow;
}

$html = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black;">
    <thead>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Unit</td>
            <td style="border: 1px solid #000000;" colspan="2">{$ubtData->unit->name}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Date</td>
            <td style="border: 1px solid #000000;" colspan="2">{$wigData->wigMeeting->meetingDate->format('M d, Y')}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Venue</td>
            <td style="border: 1px solid #000000;" colspan="2">{$wigData->wigMeeting->meetingVenue}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Time</td>
            <td style="border: 1px solid #000000;" colspan="2">{$wigData->wigMeeting->meetingTimeStart->format('g:i A')} - {$wigData->wigMeeting->meetingTimeEnd->format('g:i A')} ({$timeDifference->format('%h hrs %i mins')})</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="border: 1px solid #000000; width: 30%">Member</th>
            <th style="border: 1px solid #000000; width: 35%">Commitments</th>
            <th style="border: 1px solid #000000; width: 35%">Status</th>
        </tr>
        {$tbody}
    </tbody>
    <tfoot>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Scoreboard Update</td>
            <td style="border: 1px solid #000000;" colspan="2">
                Unit Breakthrough:&nbsp;{$ubtData->resolveUnitBreakthroughMovement($wigData->endingPeriod)}<br/>
                Lead Measure 1:&nbsp;{$ubtData->resolveLeadMeasuresMovement($wigData->endingPeriod, LeadMeasure::DESIGNATION_1)}<br/>
                Lead Measure 2:&nbsp;{$ubtData->resolveLeadMeasuresMovement($wigData->endingPeriod, LeadMeasure::DESIGNATION_2)}<br/>
            </td>
        </tr>
    </tfoot>
</table>
TABLE;
                
$pdf = new \mPDF('c', 'A4');
$pdf->mirrorMargins = .5;
$pdf->AddPage('L');
$css = file_get_contents('assets/ink/css/ink.css');
$pdf->writeHTML($css, 1);
$pdf->writeHTML($html);

$pdf->Output("RPT_WIG_MEETING_{$wigData->startingPeriod->format('Y-m-d')}_{$wigData->endingPeriod->format('Y-m-d')}.pdf", 'D');
