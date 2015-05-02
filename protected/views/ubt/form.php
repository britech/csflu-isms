<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'ubt/insert' : 'ubt/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/ubt/form.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Create Unit Breakthrough'); ?>
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
            </div>
        </div>
        <div class="control-group">
            <label><?php echo $form->renderLabel($model, 'unitBreakthroughEnvironmentStatus', array('required' => true)); ?></label>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'unitBreakthroughEnvironmentStatus', $statusList); ?>
            </div>
        </div>
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
                <?php echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
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
    <?php echo $form->endComponent(); ?>
</div>
