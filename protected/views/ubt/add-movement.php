<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('ubt/addMovement'),
    'class' => 'ink-form',
    'hasFieldset' => true
));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/ubt/add-movement.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Add UBT Movement'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <?php $this->renderPartial('commons/_validation', array('message' => $notif)); ?>
        <div class="ink-alert block" id="validation-container">
            <h4>Validation error. Please check your entries.</h4>
            <p id="validation-message"></p>
        </div>
        <div class="control-group">
            <label>WIG Session&nbsp;*</label>
            <div class="control">
                <div id="wig-input"></div>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->renderLabel($model, 'ubtFigure'); ?>
            <div class="control">
                <div id="ubt-input"></div>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->renderLabel($model, 'firstLeadMeasureFigure'); ?>
            <div class="control">
                <div id="lm1-input"></div>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->renderLabel($model, 'secondLeadMeasureFigure'); ?>
            <div class="control">
                <div id="lm2-input"></div>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->renderLabel($model, 'notes', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'notes'); ?>
                <?php echo $form->renderSubmitButton('Add', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left: 0px;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'ubtFigure'); ?>
        <?php echo $form->renderHiddenField($model, 'firstLeadMeasureFigure'); ?>
        <?php echo $form->renderHiddenField($model, 'secondLeadMeasureFigure'); ?>
        <?php echo $form->renderHiddenField($sessionModel, 'id', array('id' => 'wig')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'id', array('id' => 'ubt')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>