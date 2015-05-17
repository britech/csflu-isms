<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Activity;
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>

        <?php if ($data->activityEnvironmentStatus == Activity::STATUS_PENDING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Ongoing', array('id' => "ongoing-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/finish', 'id' => $data->id), 'Set to Finished'); ?></li>
        <?php elseif ($data->activityEnvironmentStatus == Activity::STATUS_ONGOING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Pending', array('id' => "pending-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/finish', 'id' => $data->id), 'Set to Finished'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/stop', 'id' => $data->id), 'Set to Discontinued'); ?></li>
        <?php elseif ($data->activityEnvironmentStatus == Activity::STATUS_FINISHED || $data->activityEnvironmentStatus == Activity::STATUS_DROPPED): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Ongoing', array('id' => "ongoing-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Pending', array('id' => "pending-{$data->id}")); ?></li>
        <?php endif; ?>

        <li><?php echo ApplicationUtils::generateLink(array('activity/movementLog', 'id' => $data->id), 'Movement Log'); ?></li>
    </ul>
</div>