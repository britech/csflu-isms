<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
?>
<div id="navbar">
    <div class="ink-navigation ink-grid">
        <ul class="menu horizontal green flat push-left">
            <li><?php echo ApplicationUtils::generateLink(array('site/index'), 'City of San Fernando, La Union - Integrated Strategy Management System', array('style' => 'font-weight: bold;')); ?></li>
        </ul>
        <ul class="menu horizontal green flat push-right">
            <li>
                <a href="#"><i class="fa fa-cogs"></i>&nbsp;Modules</a>
                <ul class="submenu" style="font-size: 15px;">
                    <li><?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management') ?></li>
                    <!--<li><?php echo ApplicationUtils::generateLink(array('scorecard/index'), 'Scorecard') ?></li>-->
                    <li><?php echo ApplicationUtils::generateLink(array('initiative/index'), 'Initiative') ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('ubt/index'), 'Unit Breakthrough') ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('km/index'), 'Knowledge Management'); ?></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench"></i>&nbsp;Settings</a>
                <ul class="submenu" style="font-size: 15px;">
                    <li><?php echo ApplicationUtils::generateLink(array('user/index'), 'User Management'); ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('department/index'), 'Departments'); ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('position/index'), 'Positions'); ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('uom/index'), 'Unit of Measures'); ?></li>
                </ul>
            </li>
            <li>
                <?php echo ApplicationUtils::generateLink("#", '<i class="fa fa-user"></i>&nbsp;My Profile') ?>
                <ul class="submenu" style="font-size: 15px;">
                    <li><?php echo ApplicationUtils::generateLink(array('ip/index'), 'Performance Scorecard'); ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('user/viewChangePasswordForm'), 'Change Password'); ?></li>
                    <li><?php echo ApplicationUtils::generateLink(array('site/logout'), 'Logout'); ?></li>
                </ul>
            </li>
        </ul>
    </div>
</div>