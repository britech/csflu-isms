<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#enlist', 'Account Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageAccount', 'Manage User Accounts'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageRole', 'Manage Security Roles'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageDepartment', 'Manage Departments'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#managePosition', 'Manage Positions'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageUom', 'Manage Unit of Measures'); ?></li>
        </ul>
    </div>
</div>