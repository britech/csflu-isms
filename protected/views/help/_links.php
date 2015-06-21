<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px; margin-top: 0px;">
    <div class="ink-navigation" >
        <ul class="menu vertical">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/login'), 'Login'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/indicator'), 'Indicators'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/strategyMap'), 'Strategy Map'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/measureProfile'), 'Measure Profiles'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/initiative'), 'Initiatives'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/ubt'), 'Unit Breakthroughs'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/ip'), 'Individual Performance'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/report'), 'Reports Generation'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('help/admin'), 'System Administration'); ?></li>
        </ul>
    </div>
</div>