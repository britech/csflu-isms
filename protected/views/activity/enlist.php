<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('activity/insertMovement'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/activity/enlist.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist Activity Movement'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'actualFigure'); ?>
            <div class="control">
                <div id="figure-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'budgetAmount'); ?>
            <div class="control">
                <div id="budget-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'notes', array(ModelFormGenerator::KEY_REQUIRED => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'notes'); ?>
                <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'actualFigure'); ?>
        <?php echo $form->renderHiddenField($model, 'budgetAmount'); ?>
        <?php echo $form->renderHiddenField($activity, 'id'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

