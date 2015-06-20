<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div style="position: fixed; width: 200px;">
    <div class="ink-navigation">
        <ul class="menu vertical" style="margin-top: 0px;">
            <li class="heading"><?php echo ApplicationUtils::generateLink('#', 'Contents', array('style' => 'padding-left:0px;')) ?></li>
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'Back to Application'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#login', 'Logging-in'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#multipleAccounts', 'Logging-in with Multiple Accounts'); ?></li>
            <li><?php echo ApplicationUtils::generateLink('#changePassword', 'Changing Account Password'); ?></li>
        </ul>
    </div>
</div>