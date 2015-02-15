<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'project/insertActivity' : 'project/updateActivity'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/initiative/activity-input.js"></script>
<div class="column-group half-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($model->isNew() ? "Enlist Activity" : "Update Activity"); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
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
            <?php echo $form->renderLabel($model, 'activityNumber', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'activityNumber');?>
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
            <?php echo $form->renderLabel($model, 'indicator', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'indicator'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'budgetAmount'); ?>
            <div class="control">
                <div id="budgetAmount-input" style="padding-left: 10px;"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'sourceOfBudget'); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'sourceOfBudget'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'owners', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'owners'); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline"></div>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-left:0px; margin-top:1em;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-left:0px; margin-top:1em;'));
                }
                ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'budgetAmount', array('id' => 'budgetAmount')); ?>
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

<div id="delete-activity">
    <div id="deleteActivityContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>
