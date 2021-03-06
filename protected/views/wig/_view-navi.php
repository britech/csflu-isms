<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\WigSession;
?>
<div class="ink-navigation">
    <div class="ink-navigation">
        <ul class="menu vertical">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Actions', array('style' => 'padding-left:0px;')) ?></li>

            <?php if ($data->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN): ?>
                <li><?php echo ApplicationUtils::generateLink('#', 'Update Timeline', array('id' => "update-{$data->id}")); ?></li>


                <?php if (count($data->commitments) == 0 and is_null($data->movementUpdate)): ?>
                    <li><?php echo ApplicationUtils::generateLink('#', 'Delete WIG Session', array('id' => "remove-{$data->id}")) ?></li>
                <?php else: ?>
                    <li><?php echo ApplicationUtils::generateLink(array('wig/close', 'id' => $data->id), 'Close WIG Session'); ?></li>
                <?php endif; ?>

            <?php else: ?>
                <li><?php echo ApplicationUtils::generateLink(array('report/wigMeeting', 'id' => $data->id), 'Generate WIG Meeting Report'); ?></li>
                <li><?php echo ApplicationUtils::generateLink('#', 'Refresh Data', array('id' => 'refresh')); ?></li>
            <?php endif; ?>

        </ul>
    </div>
</div>
