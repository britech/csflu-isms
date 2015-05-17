<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\initiative\Activity;
?>
<script type="text/javascript" src="protected/js/activity/_manage-navi.js"></script>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>

        <?php if ($data->activityEnvironmentStatus == Activity::STATUS_PENDING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Ongoing', array('id' => "ongoing")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/finish', 'id' => $data->id), 'Set to Finished'); ?></li>
        <?php elseif ($data->activityEnvironmentStatus == Activity::STATUS_ONGOING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Pending', array('id' => "pending")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/enlistMovement', 'id' => $data->id, 'period' => $period), 'Enlist Movement'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/finish', 'id' => $data->id, 'period' => $period), 'Set to Finished'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('activity/stop', 'id' => $data->id, 'period' => $period), 'Set to Discontinued'); ?></li>
        <?php elseif ($data->activityEnvironmentStatus == Activity::STATUS_FINISHED || $data->activityEnvironmentStatus == Activity::STATUS_DROPPED): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Ongoing', array('id' => "ongoing")); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set to Pending', array('id' => "pending")); ?></li>
        <?php endif; ?>

        <li><?php echo ApplicationUtils::generateLink(array('activity/movementLog', 'id' => $data->id), 'Movement Log'); ?></li>
    </ul>
</div>

<div id="dialog-status">
    <div id="content">
        <p><strong>Activity:</strong>&nbsp;<?php echo $data->title; ?></p>
        <p id="text"></p>
        <input type="hidden" id="activity" value="<?php echo $data->id; ?>"/>
        <input type="hidden" id="status"/>
        <input type="hidden" id="period" value="<?php echo $period; ?>"/>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>