<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;

?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('measure/create', 'map' => $map), 'Create Measure Profile') ?></li>
        <li><?php echo ApplicationUtils::generateLink('#', 'Refresh Data', array('id' => "refresh")); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('km/generateScorecard', 'map' => $map), 'Generate Scorecard Template'); ?></li>
    </ul>
</div>