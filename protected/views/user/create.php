<?php 
namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
?>
<script type="text/javascript" src="protected/js/user/create.js"></script>
<h3 style="text-align: right;">Initial Registration&nbsp;|&nbsp;<?php echo ApplicationUtils::generateLink('#', 'Need Help?', array('id'=>'open-help'))?></h3>
<div class="ink-alert block info" id="help-dialog">
    <button class="ink-dismiss" title="Click this icon to close the dialog">&times;</button>
    <h4>User Accounts Help - Initial Registration</h4>
    <p></p>
</div>
<?php 
$form = new Form(array('action'=>array('user/insertAccount'), 'class'=>'ink-form'));
echo $form->startComponent();?>
<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('ID', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('empId',array('style'=>'width: 250px;', 'id'=>'empId'));?>
        <p class="tip">Enter the ID number to continue</p>
    </div>
</div>

<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('Name', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('name', array('disabled'=>true, 'id'=>'name'));?>
        <?php echo $form->renderHiddenField('Employee[id]');?>
        <?php echo $form->renderHiddenField('Employee[lastName]');?>
        <?php echo $form->renderHiddenField('Employee[givenName]');?>
        <?php echo $form->renderHiddenField('Employee[middleName]');?>
    </div>
</div>

<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('Department', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('department', array('disabled'=>true));?>
        <?php echo $form->renderHiddenField('Department[id]');?>
    </div>
</div>

<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('Username', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('username', array('disabled'=>true));?>
        <?php echo $form->renderHiddenField('LoginAccount[username]');?>
        <?php echo $form->renderHiddenField('LoginAccount[password]');?>
        <p class="tip">During initial registration, the default password is the username.</p>
    </div>
</div>

<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('Initial Security Role', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList('SecurityRole[id]', $params['roleList'], array('disabled'=>true, 'id'=>'securityRole'));?>
    </div>
</div>

<div class="control-group column-group half-gutters" style="margin-bottom: 0px; padding-bottom: 0px;">
    <?php echo $form->renderLabel('Position', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList('Position[id]', $params['positionList'], array('disabled'=>true, 'id'=>'position'));?>
        <p class="tip">The position selected will be used only in the ISMS environment.</p>
        <?php echo $form->renderSubmitButton('Register', array('class'=>'ink-button green flat', 'style'=>'margin-top: 10px; margin-left: 0px;'));?>
    </div>
</div>
<?php 
echo $form->endComponent();

