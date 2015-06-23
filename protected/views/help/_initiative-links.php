<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#create', 'Initiative Enlistment'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#entryUpdate', 'Update Initiative Entry Data'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#manageOffice', 'Manage Implementing Offices'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#manageAlignment', 'Manage Strategy Alignments'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#managePhase', 'Manage Phases'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#manageComponent', 'Manage Components'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#manageActivity', 'Manage Activity'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#activityDashboard', 'Managing the Activity Dashboard'); ?></li>
            <li style="font-size: 15px;"><?php echo ApplicationUtils::generateLink('#movementUpdate', 'Initiative Update'); ?></li>
        </ul>
    </div>
</div>