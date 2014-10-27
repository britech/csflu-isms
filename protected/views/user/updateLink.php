<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$form = new Form(array('action'=>array('user/linkUpdate'), 'class'=>'ink-form'));
echo $form->startComponent();
?>
<script src="protected/js/user/updateLink.js" type="text/javascript"></script>
<h3 style="text-align: right;">Update Linked Security Role</h3>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Security Role', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <div id="securityRole-list"></div>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Position', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <div id="position-list"></div>
        <?php echo $form->renderSubmitButton('Update Link', array('class'=>'ink-button blue flat', 'style'=>'margin-top: 1em; margin-left: 0px;'));?>
    </div>
</div>
<?php
echo $form->renderHiddenField('UserAccount[id]', array('value'=>$params['account']));
echo $form->renderHiddenField('Employee[id]', array('value'=>$params['employee']));
echo $form->renderHiddenField('SecurityRole[id]', array('id'=>'securityRole-id', 'value'=>$params['role']));
echo $form->renderHiddenField('Position[id]', array('id'=>'position-id', 'value'=>$params['position']));
echo $form->endComponent();