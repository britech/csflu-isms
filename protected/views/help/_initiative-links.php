<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#create', 'Initiative Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#entryUpdate', 'Updating Initiative Entry Data'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageOffice', 'Managing Implementing Offices'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageAlignment', 'Managing Strategy Alignments'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#managePhase', 'Managing Phases'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageComponent', 'Managing Components'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageActivity', 'Managing Activity'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#movementUpdate', 'Initiative Update'); ?></li>
        </ul>
    </div>
</div>