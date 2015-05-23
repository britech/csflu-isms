<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div class="ink-navigation">
    <ul class="menu vertical">
        <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Action', array('style' => 'padding-left:0px;')) ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('initiative/update', 'id' => $model->id), 'Update Entry Data') ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('implementor/index', 'initiative' => $model->id), 'Manage Implementing Offices'); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('alignment/manageInitiative', 'id' => $model->id), 'Manage Strategy Alignments'); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('project/managePhases', 'initiative' => $model->id), 'Manage Phases'); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('project/manageComponents', 'initiative' => $model->id), 'Manage Components'); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('project/manageActivities', 'initiative' => $model->id), 'Manage Activities'); ?></li>
        <li><?php echo ApplicationUtils::generateLink(array('report/initiativeDetail', 'id' => $model->id), 'Generate Program of Work'); ?></li>
    </ul>
</div>