<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>
            <?php if ($model->periodDate instanceof \DateTime): ?>
            <li><?php echo ApplicationUtils::generateLink(array('scorecard/updateMovement', 'measure' => $measureProfile->id, 'period' => $period->format('Y-m')), 'Update Scorecard Movement'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('scorecard/movementLog', 'measure' => $measureProfile->id, 'period' => $period->format('Y-m')), 'Movement Log'); ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('report/scorecardUpdate', 'measure' => $measureProfile->id, 'period' => $period->format('Y-m')), 'Generate Scorecard Update'); ?></li>
        <?php else: ?>
            <li><?php echo ApplicationUtils::generateLink(array('scorecard/enlistMovement', 'measure' => $measureProfile->id, 'period' => $period->format('Y-m')), 'Enlist Scorecard Movement'); ?></li> 
        <?php endif; ?>
    </ul>
</div>

