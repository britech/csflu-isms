<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#create', 'Strategy Map Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#entryUpdate', 'Update Strategy Map Entry Data'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#managePerspective', 'Manage Perspectives'); ?></li>
        </ul>
    </div>
</div>