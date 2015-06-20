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
        </ul>
    </div>
</div>