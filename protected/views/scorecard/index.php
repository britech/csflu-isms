<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <th style="text-align: left; width: 50%;">Initiative</th>
            <th style="text-align: left; width: 25%;">Accomplishment Rate</th>
            <th style="text-align: left; width: 25%;">Budget Burn Rate</th>
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
                    <td><?php echo $initiative->resolveAccomplishmentRate($period) ?></td>
                    <td><?php echo $initiative->resolveBudgetBurnRate($period) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<table class="ink-table bordered">
    <thead>
        <tr>
            <th style="text-align: left; width: 25%;" colspan="1">Unit</th>
            <th style="text-align: left; width: 50%;" colspan="2">Unit Breakthrough and Lead Measures</th>
            <th style="text-align: left; width: 25%;" colspan="3">Movement Value</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($unitBreakthroughs) == 0): ?>
            <tr>
                <td colspan="4">No Initiatives Aligned</td>
            </tr>
        <?php else: ?>
            <?php
            foreach ($unitBreakthroughs as $unitBreakthrough):
                $unitBreakthrough->filterLeadMeasures($period);
                ?>
                <tr>
                    <td rowspan="3"><?php echo $unitBreakthrough->unit->name; ?></td>
                    <td style="width: 10%; font-size: 10px;">Unit Breakthrough</td>
                    <td><?php echo $unitBreakthrough->description; ?></td>
                    <td><?php echo $unitBreakthrough->resolveUnitBreakthroughMovement($period); ?></td>
                </tr>
                <tr>
                    <td style="border-left: #bbbbbb solid 1px; font-size: 10px;">Lead Measure 1</td>
                    <td><?php echo $unitBreakthrough->leadMeasures[0]->description ?></td>
                    <td><?php echo $unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_1); ?></td>
                </tr>
                <tr>
                    <td style="border-left: #bbbbbb solid 1px; font-size: 10px;">Lead Measure 2</td>
                    <td><?php echo $unitBreakthrough->leadMeasures[1]->description ?></td>
                    <td><?php echo $unitBreakthrough->resolveLeadMeasuresMovement($period, LeadMeasure::DESIGNATION_2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>