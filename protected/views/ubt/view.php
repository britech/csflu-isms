<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;
?>
<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>

<div class="ink-alert block info" style="margin-top: 0px;">
    <h4><?php echo $data->description; ?></h4>
    <p>
        <strong>Unit</strong>
        <span style="display: block; margin-bottom: 10px;"><?php echo $data->unit->name; ?></span>

        <strong>Lead Measures</strong>
        <?php foreach ($data->leadMeasures as $leadMeasure): ?>
            <?php if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE): ?>
                <span style="display: block;">*&nbsp;<?php echo $leadMeasure->description; ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <strong style="display: block; margin-top: 10px;">Timeline</strong>
        <span><?php echo "{$data->startingPeriod->format('F-Y')} - {$data->endingPeriod->format('F-Y')}"; ?></span>
    </p>
</div>