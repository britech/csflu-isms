<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#create', 'Measure Profile Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#entryUpdate', 'Update Measure Profile Entry Data'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageOffice', 'Manage Lead Offices'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageTarget', 'Manage Targets'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#movement', 'Measure Profile Movement'); ?></li>
        </ul>
    </div>
</div>