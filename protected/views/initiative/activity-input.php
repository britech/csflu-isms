<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('project/insertActivity'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/initiative/activity-input.js"></script>
<div class="column-group half-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader("Enlist Activity"); ?>
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
            <label>Component&nbsp;*</label>
            <div class="control">
                <div id="component-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'title', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'title'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'descriptionOfTarget', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'descriptionOfTarget'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'targetFigure'); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'targetFigure'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'budgetAmount'); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'budgetAmount'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'sourceOfBudget'); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'sourceOfBudget'); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline"></div>
                <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-left:0px; margin-top:1em;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'activity-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'activity-end')); ?>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($componentModel, 'id', array('id' => 'component')); ?>
        <?php echo $form->renderHiddenField($initiativeModel, 'id', array('id' => 'initiative')); ?>
        <?php echo $form->renderHiddenField($initiativeModel, 'startingPeriod', array('id' => 'initiative-start')); ?>
        <?php echo $form->renderHiddenField($initiativeModel, 'endingPeriod', array('id' => 'initiative-end')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="activity-list"></div>
    </div>
</div>
