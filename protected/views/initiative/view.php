<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>

<div class="ink-alert block info" style="margin-top: 0px;">
    <h4><?php echo $model->title; ?></h4>
    <p>
        <strong>Description</strong>
        <span style="display: block; margin-bottom: 10px;"><?php echo $model->description; ?></span>

        <strong>Objectives</strong>
        <?php foreach ($model->objectives as $objective): ?>
            <span style="display: block;">*&nbsp;<?php echo $objective->description; ?></span>
        <?php endforeach; ?>

        <strong style="display: block; margin-top: 10px;">Beneficiaries</strong>
        <span style="display: block; margin-bottom: 10px;"><?php echo implode(", ", explode('+', $model->beneficiaries));?></span>
        
        <strong>Timeline</strong>
        <span style="display: block;"><?php echo "{$model->startingPeriod->format('F-Y')} to {$model->endingPeriod->format('F-Y')}";?></span>
    </p>
</div>