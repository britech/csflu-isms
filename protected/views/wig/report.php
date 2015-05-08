<?php

namespace org\csflu\isms\views;

ob_end_clean();
error_reporting(0);

$tbody = "";
foreach ($accounts as $account) {
    foreach ($sessionModel->commitments as $commitment) {
        if ($commitment->user->id == $account->id) {
            $firstEntry = $commitment;
            break;
        }
    }

    $firstEntryStatus = "";
    if (count($firstEntry->commitmentMovements) == 0) {
        $firstEntryStatus = "N/A";
    } else {
        foreach ($firstEntry->commitmentMovements as $movement) {
            $firstEntryStatus.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
        }
    }

    $otherCommitments = "";
    foreach ($sessionModel->commitments as $data) {
        if ($data->id != $firstEntry->id && $data->user->id == $account->id) {
            $succeedingData .= <<<TABLE
<tr>
    <td style="border-left: 1px solid #bbb;"><?php echo "{$data->commitment} ({$data->translateStatusCode()})"; ?></td>
                        <td>
                            <?php
                            if (count($data->commitmentMovements) == 0) {
                                echo "N/A";
                            } else {
                                $movementData = "";
                                foreach ($data->commitmentMovements as $movement) {
                                    $movementData.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
                                }
                                echo nl2br($movementData);
                            }
                            ?>
                        </td>
                    </tr>
TABLE;
        }
    }
    $tbody.=<<<TABLE
<tr>
  <td style="border: 1px solid #000000;" rowspan="{$collatedCommitments[$account->id]->countAll()}">{$account->employee->givenName} {$account->employee->lastName}</td>
  <td style="border: 1px solid #000000;">{$firstEntry->commitment}</td>
  <td style="border: 1px solid #000000;">{$firstEntryStatus}</td>
</tr>
TABLE;
}



$html = <<<TABLE
<table class="ink-table bordered" style="font-family: sans-serif; color: black;">
    <thead>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">Unit</td>
            <td style="border: 1px solid #000000;" colspan="2">{$ubtData->unit->name}</td>
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
                Unit Breakthrough:&nbsp;{$ubtFigure} {$ubtData->uom->getAppropriateUomDisplay()}<br/>
                Lead Measure 1:&nbsp;{$lm1Figure} {$lm1Data->uom->getAppropriateUomDisplay()}<br/>
                Lead Measure 2:&nbsp;{$lm2Figure} {$lm2Data->uom->getAppropriateUomDisplay()}<br/>
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

$pdf->Output("WIG-REPORT_{$wigData->startingPeriod->format('Y-m-d')}_{$wigData->endingPeriod->format('Y-m-d')}.pdf", 'D');
