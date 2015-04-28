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
<div class="column-group quarter-gutters">
    <!-- start partition 1-->
    <div class="all-50">
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'unit', array('required' => true)); ?>
            <div class="control">
                <div id="department-input"></div>
            </div>
        </div>
        <div class="control-group">
            <label><?php echo $form->renderLabel($model, 'description', array('required' => true)); ?></label>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'description'); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>
                <?php echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
    </div>
    <!-- end partition 1-->

    <!-- start partition 2-->
    <div class="all-50">
        <div class="column-group quarter-gutters">
            <div class="all-50">
                <div class="control-group">
                    <label><?php echo $form->renderLabel($model, 'baselineFigure', array('required' => true)); ?></label>
                    <div class="control">
                       <div id="baseline-input"></div>
                    </div>
                </div>
            </div>
            <div class="all-50">
                <div class="control-group">
                    <label><?php echo $form->renderLabel($model, 'targetFigure', array('required' => true)); ?></label>
                    <div class="control">
                        <div id="target-input"></div>
                    </div>
                </div>
            </div>
            <div class="all-100">
                <div class="control-group">
                    <label><?php echo $form->renderLabel($model, 'uom', array('required' => true)); ?></label>
                    <div class="control">
                        <div id="uom-input"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'objectives', array('required' => true)); ?>
            <div class="control">
                <div id="objectives-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'measures', array('required' => true)); ?>
            <div class="control">
                <div id="measures-input"></div>
            </div>
        </div>
    </div>
    <!-- end partition 2-->
</div>
<?php echo $form->renderHiddenField($model, 'validationMode'); ?>
<?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'ubt-start')); ?>
<?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'ubt-end')); ?>
<?php echo $form->renderHiddenField($model, 'baselineFigure', array('id' => 'baseline')); ?>
<?php echo $form->renderHiddenField($model, 'targetFigure', array('id' => 'target')); ?>
<?php echo $form->renderHiddenField($uomModel, 'id', array('id' => 'uom')); ?>
<?php echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'department')); ?>
<?php echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objectives')); ?>
<?php echo $form->renderHiddenField($measureProfileModel, 'id', array('id' => 'measures')); ?>
<?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
<?php echo $form->renderHiddenField($mapModel, 'startingPeriodDate', array('id' => 'map-start')); ?>
<?php echo $form->renderHiddenField($mapModel, 'endingPeriodDate', array('id' => 'map-end')); ?>
<?php
echo $form->endComponent();
