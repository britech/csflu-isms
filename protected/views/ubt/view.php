<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\LeadMeasure;
?>
<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>

<div class="ink-alert block info" style="margin-top: 0px;">
    <h4><?php echo $data->unit->name; ?></h4>
    <p>
        <strong>Unit Breakthrough</strong>
        <span style="display: block; margin-bottom: 10px;"><?php echo $data->description; ?></span>

        <strong>Lead Measure 1</strong>
        <?php foreach ($data->leadMeasures as $leadMeasure): ?>
            <?php if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE && $leadMeasure->designation == LeadMeasure::DESIGNATION_1): ?>
                <span style="display: block"><?php echo $leadMeasure->description; ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <strong style="display: block; margin-top: 10px;">Lead Measure 2</strong>
        <?php foreach ($data->leadMeasures as $leadMeasure): ?>
            <?php if ($leadMeasure->leadMeasureEnvironmentStatus == LeadMeasure::STATUS_ACTIVE && $leadMeasure->designation == LeadMeasure::DESIGNATION_2): ?>
                <span style="display: block;"><?php echo $leadMeasure->description; ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <strong style="display: block; margin-top: 10px;">Timeline</strong>
        <span><?php echo "{$data->startingPeriod->format('F-Y')} - {$data->endingPeriod->format('F-Y')}"; ?></span>
    </p>
</div>