<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('commitment/insertMovement'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<?php echo $form->startComponent(); ?>
<?php echo $form->constructHeader('Add Movement Data'); ?>
<div class="ink-alert basic info">
    <strong>Important Note:</strong>&nbsp;Fields with * are required.
</div>
<div class="ink-alert block" id="validation-container">
    <h4>Validation error. Please check your entries</h4>
    <p id="validation-message"></p>
</div>
<?php
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<div class="control-group">
    <?php echo $form->renderLabel($model, 'commitment'); ?>
    <div class="control">
        <?php echo $form->renderTextField($model, 'commitment', array('readonly' => true)); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel($movementModel, 'movementFigure', array('required' => true)); ?>
    <div class="control">
        <?php echo $form->renderTextField($movementModel, 'movementFigure'); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel($movementModel, 'notes', array('required' => true)); ?>
    <div class="control">
        <?php echo $form->renderTextArea($movementModel, 'notes'); ?>
        <?php echo $form->renderSubmitButton('Add', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left:0px;')) ?>
    </div>
</div>
<?php echo $form->renderHiddenField($model, 'id', array('id' => 'commitment')); ?>
<?php echo $form->endComponent();
