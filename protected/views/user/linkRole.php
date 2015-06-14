<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$form = new Form(array('action' => array('user/linkRole'), 'class' => 'ink-form'));
echo $form->startComponent();
?>
<script src="protected/js/user/linkRole.js" type="text/javascript"></script>
<h3 style="text-align: right;">Link Security Role</h3>
<div class="control-group column-group half-gutters">
<?php echo $form->renderLabel('Security Role', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <div id="securityRole-list"></div>
    </div>
</div>
<div class="control-group column-group half-gutters">
<?php echo $form->renderLabel('Department', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <div id="department-list"></div>
    </div>
</div>
<div class="control-group column-group half-gutters">
<?php echo $form->renderLabel('Position', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <div id="position-list"></div>
<?php echo $form->renderSubmitButton('Link to Account', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
    </div>
</div>
<?php
echo $form->renderHiddenField('Employee[id]', array('value' => $params['employee'], 'id' => 'employee'));
echo $form->renderHiddenField('SecurityRole[id]', array('id' => 'securityRole-id'));
echo $form->renderHiddenField('Department[id]', array('id' => 'department-id'));
echo $form->renderHiddenField('Position[id]', array('id' => 'position-id'));
echo $form->endComponent();

