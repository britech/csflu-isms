<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\indicator\Indicator;
?>
<script type="text/javascript" src="protected/js/measure-profile/view.js"></script>
<?php $this->renderPartial('commons/_notification', array('notif' => $params['notif'])); ?>
<table class="ink-table alternating bordered">
    <tbody>
        <tr>
            <th style="text-align: center; color: white; background-color: black;" colspan="2">Entry Data</th>
        </tr>
        <tr>
            <th style="text-align: right; width: 20%;">Objective</th>
            <td><?php echo $model->objective->description; ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Indicator</th>
            <td><?php echo $model->indicator->description; ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Rationale</th>
            <td><?php echo $model->indicator->rationale; ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Formula</th>
            <td><?php echo $model->indicator->formula; ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Frequency</th>
            <td>
                <?php
                $data = array();
                $input = explode($model->arrayDelimiter, $model->frequencyOfMeasure);
                for ($i = 0; $i < count($input); $i++) {
                    array_push($data, MeasureProfile::getFrequencyTypes()[$input[$i]]);
                }
                echo implode($model->arrayDelimiter, $data);
                ?>
            </td>
        </tr>
        <tr>
            <th style="text-align: right;">Unit Of Measure</th>
            <td><?php echo $model->indicator->uom->description; ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Source of Data</th>
            <td><?php echo nl2br(implode('\n', explode($model->arrayDelimiter, $model->indicator->dataSource))); ?></td>
        </tr>
        <tr>
            <th style="text-align: right;">Status of Data</th>
            <td><?php echo Indicator::getDataSourceDescriptionList()[$model->indicator->dataSourceStatus]; ?></td>
        </tr>
        <?php if ($model->indicator->dataSourceStatus !== Indicator::STAT_AVAILABLE): ?>
            <tr>
                <th style="text-align: right;">Date of Availability</th>
                <td><?php echo $model->indicator->dataSourceAvailabilityDate; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th style="text-align: right;">Timeline</th>
            <td><?php echo $model->timelineStart->format('F Y') . ' to ' . $model->timelineEnd->format('F Y'); ?></td>
        </tr>
        <tr>
            <th style="text-align: center; color: white; background-color: black;" colspan="2">Lead Offices</th>
        </tr>
        <tr>
            <td colspan="2">
                <div id="leadoffice-<?php echo $model->id; ?>"></div>
            </td>
        </tr>

        <tr>
            <th style="text-align: center; color: white; background-color: black;" colspan="2">Baseline Data</th>
        </tr>
        <tr>
            <td colspan="2">
                <div id="baseline-<?php echo $model->indicator->id; ?>"></div>
            </td>
        </tr>

        <tr>
            <th style="text-align: center; color: white; background-color: black;" colspan="2">Target Data</th>
        </tr>
        <tr>
            <td colspan="2">
                <div id="target-<?php echo $model->id; ?>"></div>
            </td>
        </tr>
    </tbody>
</table>