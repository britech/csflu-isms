<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#enlist', 'Unit Breakthrough Enlistment'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#update', 'Update Unit Breakthrough Entry Data'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageAlignment', 'Manage Strategy Alignments'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageLeadMeasure', 'Manage Lead Measures'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#manageWig', 'Managing WIG Sessions')?></li>
            <li><?php echo ApplicationUtils::generateLink('#movementUpdate', 'Record Unit Breakthrough Movements')?></li>
        </ul>
    </div>
</div>