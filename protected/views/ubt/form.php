<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'ubt/insert' : 'ubt/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/ubt/form.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($model->isNew() ? 'Create Unit Breakthrough' : 'Update Unit Breakthrough'); ?>
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
            <label>Department&nbsp;*</label>
            <div class="control">
                <div id="department-input"></div>
            </div>
        </div>
        <div class="control-group">
            <label>Unit Breakthrough&nbsp;*</label>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'description'); ?>
            </div>
        </div>
        <?php if ($model->isNew()): ?>
            <div class="control-group">
                <label>Lead Measures&nbsp;*</label>
                <div class="control">
                    <?php echo $form->renderTextArea($leadMeasureModel, 'description', array('id' => 'leadMeasures')); ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form->renderLabel($objectiveModel, 'description', array('required' => true)); ?>
                <div class="control">
                    <div id="objectives-input"></div>
                </div>
            </div>
            <div class="control-group">
                <label>Indicator&nbsp;*</label>
                <div class="control">
                    <div id="measures-input"></div>
                </div>
            </div>
        <?php endif; ?>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>
            </div>
        </div>
        <?php
        if ($model->isNew()) {
            echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left:0px;'));
        } else {
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left:0px;'));
        }
        ?>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'validationMode'); ?>
        <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'ubt-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'ubt-end')); ?>
        <?php echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'department')); ?>
        <?php
        if ($model->isNew()) {
            echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objectives'));
            echo $form->renderHiddenField($measureProfileModel, 'id', array('id' => 'measures'));
        }
        ?>
        <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'startingPeriodDate', array('id' => 'map-start')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'endingPeriodDate', array('id' => 'map-end')); ?>
<?php echo $form->endComponent(); ?>
    </div>
</div>
