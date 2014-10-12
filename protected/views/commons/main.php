<?php
namespace org\csflu\isms\views; 
use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="assets/ink/css/ink.css" rel="stylesheet" type="text/css"/>
        <link href="assets/ink/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="assets/app.css" rel="stylesheet" type="text/css"/>
        <title><?php echo $this->title; ?></title>
    </head>
    <body style="background-color: #eee;">
        <?php 
        if(!empty($_SESSION['user']) && empty($params['exception'])):?>
        <div id="navbar">
            <div class="ink-navigation ink-grid">
                <ul class="menu horizontal green flat push-left">
                    <li><?php
                        echo ApplicationUtils::generateLink(array('site/index'), 'City of San Fernando, La Union - Integrated Strategy Management System', array('style' => 'font-weight: bold; padding-left: 0px;'))
                        ?></li>
                </ul>
                <ul class="menu horizontal green flat push-right">
                    <li>
                        <a href="#"><i class="fa fa-cogs"></i>&nbsp;Modules</a>
                        <ul class="submenu" style="font-size: 15px;">
                            <li><?php echo ApplicationUtils::generateLink(array('strategyMap/index'), 'Strategy Map') ?></li>
                            <li><?php echo ApplicationUtils::generateLink(array('scorecard/index'), 'Scorecard') ?></li>
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
                        <?php echo ApplicationUtils::generateLink("#", '<i class="fa fa-user"></i>&nbsp;My Profile')?>
                        <ul class="submenu" style="font-size: 15px;">
                            <li><?php echo ApplicationUtils::generateLink(array('ip/index'), 'Performance Scorecard'); ?></li>
                            <li><?php echo ApplicationUtils::generateLink(array('site/logout'), 'Logout'); ?></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="ink-grid" style="margin-top: 5%;">
            <?php include_once $body; ?>
            
            <div class="column-group quarter-gutters" id="footer">
                <div class="all-25 push-center align-center" style="border-top: 1px solid black;">
                    <small style="font-size: 10px;">
                        <span>
                            Copyright &copy;&nbsp;<?php echo date('Y');?>
                            &nbsp;|&nbsp;
                            <?php echo ApplicationUtils::generateLink('http://new.sanfernandocity.gov.ph', 'About Us', array('target'=>'_new'))?>
                            &nbsp;|&nbsp;
                            <?php echo ApplicationUtils::generateLink('#', 'Report Issues/Bugs')?>
                        </span>
                        <span style="display: block;">Information Technology Section - Office of the City Administrator</span>
                        <span style="display: block;">City of San Fernando, La Union</span>
                    </small>
                </div>
            </div>
        </div>
        <?php else:?>
        <div class="ink-grid" style="margin-top: 5%;">
            <?php include_once $body; ?>
        </div>
        <?php endif;?>
        
    </body>
</html>