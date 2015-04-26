<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

if (isset($finishIndicator)) {
    $url = array('commitment/insertMovement', 'isFinished' => $finishIndicator);
} else {
    $url = array('commitment/insertMovement');
}

$form = new ModelFormGenerator(array(
    'action' => $url,
    'class' => 'ink-form',
    'hasFieldset' => true
));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/commitment/_movement.js"></script>
<?php echo $form->startComponent(); ?>
<?php echo $form->constructHeader('Add Movement Data'); ?>
<div class="ink-alert basic info">
    <strong>Important Note:</strong>&nbsp;Fields with * are required.
</div>
<div id="validation-container"></div>
<?php
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<div class="control-group">
    <?php echo $form->renderLabel($model, 'commitment'); ?>
    <div class="control">
        <?php echo $form->renderTextField($model, 'commitment', array('readonly' => true, 'id' => 'commitment')); ?>
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
<?php echo $form->renderHiddenField($model->user, 'id', array('id' => 'user')); ?>
<?php echo $form->renderHiddenField($model, 'commitmentEnvironmentStatus'); ?>
<?php echo $form->renderHiddenField($model, 'id', array('id' => 'commitment')); ?>
<?php
echo $form->endComponent();
