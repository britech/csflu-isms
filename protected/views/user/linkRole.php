<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('user/linkRole'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script src="protected/js/user/linkRole.js" type="text/javascript"></script>
<?php echo $form->startComponent(); ?>
<?php echo $form->constructHeader("Link Security Role"); ?>
<div class="ink-alert basic info">
    <strong>Important Note:</strong>&nbsp;Fields with * are required.
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'securityRole', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <div id="securityRole-list"></div>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($employee, 'department', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <div id="department-list"></div>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($employee, 'position', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <div id="position-list"></div>
        <?php echo $form->renderSubmitButton('Link to Account', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
    </div>
</div>
<?php
echo $form->renderHiddenField($employee, 'id', array('id' => 'employee'));
echo $form->renderHiddenField($securityRole, 'id', array('id' => 'securityRole-id'));
echo $form->renderHiddenField($department, 'id', array('id' => 'department-id'));
echo $form->renderHiddenField($position, 'id', array('id' => 'position-id'));
echo $form->endComponent();

