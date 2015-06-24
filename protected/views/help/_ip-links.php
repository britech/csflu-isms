<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#create', 'Commitments Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#delete', 'Delete Commitment Entry'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manage', 'Managing the Commitment Dashboard'); ?></li>
        </ul>
    </div>
</div>