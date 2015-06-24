<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\Commitment;
?>
<script type="text/javascript" src="protected/js/commitment/_navigation.js"></script>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Actions', array('style' => 'padding-left: 0px;')); ?></li>
        <?php if ($data->commitmentEnvironmentStatus == Commitment::STATUS_PENDING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set as Ongoing', array('id' => "ongoing-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/finish', 'id' => $data->id), 'Set To Finished'); ?></li>
            <?php if (count($data->commitmentMovements) == 0): ?>
                <li><?php echo ApplicationUtils::generateLink('#', 'Delete Commitment', array('id' => "remove-{$data->id}")); ?></li>
            <?php endif; ?>
        <?php elseif ($data->commitmentEnvironmentStatus == Commitment::STATUS_ONGOING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set as Pending', array('id' => "pending-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/finish', 'id' => $data->id), 'Set To Finished'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/movementLog', 'commitment' => $data->id), 'Movement Log'); ?></li>
        <?php elseif ($data->commitmentEnvironmentStatus == Commitment::STATUS_FINISHED): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set as Pending', array('id' => "pending-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set as Ongoing', array('id' => "ongoing-{$data->id}")); ?></li>
        <?php endif; ?>
    </ul>
</div>

<div id="dialog-pending">
    <div id="dialogPendingContent" style="overflow: hidden">
        <p id="text-pending"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-pending">Yes</button>
            <button class="ink-button green flat" id="deny-pending">No</button>
        </div>
    </div>
</div>

<div id="dialog-ongoing">
    <div id="dialogPendingContent" style="overflow: hidden">
        <p id="text-ongoing"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-ongoing">Yes</button>
            <button class="ink-button green flat" id="deny-ongoing">No</button>
        </div>
    </div>
</div>