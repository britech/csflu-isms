<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;

$this->renderPartial('commons/_notification', array('notif' => $notif));
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <th colspan="3">SCORECARD MOVEMENT</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 50%; font-weight: bold;" rowspan="2"><?php echo $measureProfile->indicator->description; ?></td>
            <td style="width: 25%;">Movement as of<br/><?php echo $period->format('F Y') ?></td>
            <td style="width: 25%;">Remarks</td>
        </tr>
        <tr>
            <td style="width: 25%; font-weight: bold; border-left: #bbbbbb solid 1px;"><?php echo $measureProfile->resolveLatestMovementValue($period); ?></td>
            <td style="width: 25%; font-weight: bold;"><?php echo $measureProfile->resolveLatestMovementRemarks($period); ?></td>
        </tr>
    </tbody>
</table>

<table class="ink-table bordered">
    <thead>
        <tr>
            <th colspan="4">INITIATIVES</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 33%">Initiative</th>
            <th style="text-align: left; width: 33%">Accomplishment Rate</th>
            <th style="text-align: left; width: 33%">Budget Burn Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($initiatives) == 0): ?>
            <tr>
                <td colspan="3">No Initiatives Aligned</td>
            </tr>
        <?php else: ?>
            <?php foreach ($initiatives as $initiative): ?>
                <tr>
                    <td><?php echo $initiative->title; ?></td>
                    <?php
                    if (empty($initiative->countActivities())):
                        ?>
                        <td colspan="2">No activities defined</td>
                    <?php else: ?>
                        <td><?php echo $initiative->resolveTotalAccomplishmentRate($period) ?></td>
                        <td><?php echo $initiative->resolveTotalBudgetBurnRate($period) ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<table class="ink-table bordered">
    <thead>
        <tr>
            <th colspan="4">UNIT BREAKTHROUGHS</th>
        </tr>
        <tr>
            <th style="text-align: left; width: 25%;" colspan="1">Unit</th>
            <th style="text-align: left; width: 50%;" colspan="2">Unit Breakthrough and Lead Measures</th>
            <th style="text-align: left; width: 25%;" colspan="1">Movement Value</th>
        </tr>
    </thead>
    <tbody>       
        <?php if (count($unitBreakthroughs) == 0): ?>
            <tr>
                <td colspan="4">No Unit Breakthroughs Aligned</td>
            </tr>
        <?php else: ?>
            <?php
            foreach ($unitBreakthroughs as $unitBreakthrough):
                $leadMeasures = $unitBreakthrough->filterLeadMeasures($period);
                ?>
                <tr>
                    <td rowspan="3"><?php echo $unitBreakthrough->unit->name; ?></td>
                    <td style="width: 10%; font-size: 10px;">Unit Breakthrough</td>
                    <td><?php echo $unitBreakthrough->description; ?></td>
                    <td><?php echo $unitBreakthrough->resolveUnitBreakthroughMovement($period); ?></td>
                </tr>
                <tr>
                    <td style="border-left: #bbbbbb solid 1px; font-size: 10px;">Lead Measure 1</td>
                    <td><?php echo $leadMeasures[0]->description ?></td>
                    <td><?php echo $unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_1); ?></td>
                </tr>
                <tr>
                    <td style="border-left: #bbbbbb solid 1px; font-size: 10px;">Lead Measure 2</td>
                    <td><?php echo $leadMeasures[1]->description ?></td>
                    <td><?php echo $unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>