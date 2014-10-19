<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
?>
<script src="protected/js/user/changePassword.js" type="text/javascript"></script>
<div class="all-50 push-center" style="margin-bottom: 10px;">
    <?php
        $form = new Form(array('action'=>array('user/changePassword'),'class'=>'ink-form','hasFieldset'=>true));
        echo $form->startComponent();
        echo $form->constructHeader('Change Password Module');
    
        if(isset($params['notif']) && !empty($params['notif'])):
    ?>
    <div class="ink-alert basic info"><?php echo $params['notif'];?></div>
    <?php endif;?>
    <div class="control-group column-group quarter-gutters">
        <?php echo $form->renderLabel('Old Password', array('class'=>'all-25 align-right'));?>
        <div class="control all-75">
            <?php echo $form->renderPasswordField('oldPassword');?>
            <p class="tip" id="oldPassword-tip"></p>
        </div>
    </div>
    <div class="control-group column-group quarter-gutters">
        <?php echo $form->renderLabel('New Password', array('class'=>'all-25 align-right'));?>
        <div class="control all-75">
            <?php echo $form->renderPasswordField('newPassword', array('disabled'=>true));?>
            <p class="tip" id="newPassword-tip"></p>
        </div>
    </div>
    <div class="control-group column-group quarter-gutters">
        <?php echo $form->renderLabel('Confirm Password', array('class'=>'all-25 align-right'));?>
        <div class="control all-75">
            <?php echo $form->renderPasswordField('confirmPassword', array('disabled'=>true));?>
            <p class="tip" id="confirmPassword-tip"></p>
            <?php echo $form->renderSubmitButton('Update', array('style'=>'margin-top: 1em; margin-left: 0px;', 'class'=>'ink-button green flat', 'id'=>'btnSubmit'))?>
        </div>
    </div>
    <?php echo $form->renderHiddenField('LoginAccount[password]', array('id'=>'password'));?>
    <?php echo $form->endComponent();?>
</div>