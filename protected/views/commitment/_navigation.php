<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\Commitment;
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Actions', array('style' => 'padding-left: 0px;')); ?></li>
        <?php if ($data->commitmentEnvironmentStatus == Commitment::STATUS_PENDING): ?>
            <li><?php echo ApplicationUtils::generateLink('#', 'Set as Ongoing', array('id' => "pending-{$data->id}")); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/finish', 'id' => $data->id), 'Set To Finished'); ?></li>
            <?php if (!isset($data->commitmentMovements) OR count($data->commitmentMovements) == 0): ?>
                <li><?php echo ApplicationUtils::generateLink('#', 'Delete Commitment', array('id' => "remove-{$data->id}")); ?></li>
            <?php endif; ?>
        <?php elseif ($data->commitmentEnvironmentStatus == Commitment::STATUS_ONGOING): ?>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/finish', 'id' => $data->id), 'Set To Finished'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('commitment/movementLog', 'commitment' => $data->id), 'Movement Log'); ?></li>
        <?php endif; ?>
    </ul>
</div>