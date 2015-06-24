<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 230px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#measureProfile', 'Measure Profile'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#scorecardUpdate', 'Scorecard Update'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#scorecardTemp', 'Scorecard Template'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#iniPow', 'Program of Work'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#iniUpdate', 'Initiative Update Template'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#wigMeeting', 'WIG Meeting Template'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#indScorecard', 'Individual Performance Scorecard'); ?></li>
        </ul>
    </div>
</div>