<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
?>
<div class="column-group quarter-gutters" id="footer">
    <div class="all-25 push-center align-center" style="border-top: 1px solid black;">
        <small style="font-size: 10px;">
            <span>
                Copyright &copy;&nbsp;<?php echo date('Y'); ?>
                &nbsp;|&nbsp;
                <?php echo ApplicationUtils::generateLink('http://new.sanfernandocity.gov.ph', 'About Us', array('target' => '_new')) ?>
                &nbsp;|&nbsp;
                <?php echo ApplicationUtils::generateLink('https://github.com/britech/csflu-isms/issues', 'Report Issues/Bugs', array('target'=>'_new')) ?>
            </span>
            <span style="display: block;">Information Technology Section - Office of the City Administrator</span>
            <span style="display: block;">City of San Fernando, La Union</span>
        </small>
    </div>
</div>