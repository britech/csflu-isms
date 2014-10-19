<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div class="ink-alert block info" style="margin-top: 0px;">
    <h4>Account Information</h4>
    <p>
        <span style="display: block; font-weight: bold;">Owner</span>
        <span><?php echo $params['name'];?></span>
        
        <span style="display: block; font-weight: bold; margin-top: 10px">Username</span>
        <span><?php echo $params['username'];?></span>
        
        <span style="display: block; margin-top: 20px; font-weight: bold; border-top: 1px solid black; text-align: right">Actions</span>
        <?php if($params['status']==1):?>
        <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/linkForm', 'id'=>$params['employee']), 'Link a Security Role')?></span>
        <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/confirmResetPassword', 'id'=>$params['employee']), 'Reset Password')?></span>
        <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/confirmStatusToggle', 'id'=>$params['employee'], 'stat'=>0), 'Disable Account')?></span>
        <?php elseif($params['status']==0):?>
        <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/confirmStatusToggle', 'id'=>$params['employee'], 'stat'=>1), 'Activate Account')?></span>
        <?php endif;?>
    </p>
</div>