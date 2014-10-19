<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$form = new Form(array('action'=>array('user/linkRole'), 'class'=>'ink-form'));
echo $form->startComponent();
?>
<h3 style="text-align: right;">Link Security Role</h3>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Security Role', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList('SecurityRole[id]', $params['roles']);?>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Department', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList('Department[id]', $params['departments']);?>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Position', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList('Position[id]', $params['positions']);?>
        <?php echo $form->renderSubmitButton('Link to Account', array('class'=>'ink-button green flat', 'style'=>'margin-top: 1em; margin-left: 0px;'));?>
    </div>
</div>
<?php 
echo $form->renderHiddenField('Employee[id]', array('value'=>$params['employee']));
echo $form->endComponent();

