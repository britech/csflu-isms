<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>
        <li><?php echo ApplicationUtils::generateLink('#', 'Refresh Data', array('id' => 'refresh')); ?></li>
    </ul>
</div>