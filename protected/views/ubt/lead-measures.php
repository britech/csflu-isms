<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'leadMeasure/insert' : 'leadMeasure/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/ubt/lead-measures.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($model->isNew() ? 'Enlist Lead Measures' : 'Update Lead Measure'); ?>

        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div id="validation-container"></div>

        <div class="control-group">
            <?php echo $form->renderLabel($model, 'description', array('required' => true)); ?>
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

        <div class="column-group quarter-gutters">
            <div class="all-50">
                <div class="control-group">
                    <?php echo $form->renderLabel($model, 'designation', array('required' => true)); ?>
                    <div class="control">
                        <?php echo $form->renderDropDownList($model, 'designation', $designationList); ?>
                    </div>
                </div>
            </div>
            <div class="all-50">
                <div class="control-group">
                    <?php echo $form->renderLabel($model, 'leadMeasureEnvironmentStatus', array('required' => true)); ?>
                    <div class="control">
                        <?php echo $form->renderDropDownList($model, 'leadMeasureEnvironmentStatus', $statusList); ?>
                    </div>
                </div>
            </div>
            <div class="all-50">
                <div class="control-group">
                    <?php echo $form->renderLabel($model, 'baselineFigure', array('required' => true)); ?>
                    <div class="control">
                        <div id="baseline-input"></div>
                    </div>
                </div>
            </div>
            <div class="all-50">
                <div class="control-group">
                    <?php echo $form->renderLabel($model, 'targetFigure', array('required' => true)); ?>
                    <div class="control">
                        <div id="target-input"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="control-group column-group quarter-gutters">
            <?php echo $form->renderLabel($model, 'uom', array('required' => true, 'class' => 'all-30 content-right')); ?>
            <div class="control all-70">
                <div id="uom-input"></div>
            </div>
        </div>
        <?php
        if ($model->isNew()) {
            echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top:10px; margin-left:0px;'));
        } else {
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:10px; margin-left:0px;'));
        }
        ?>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'baselineFigure'); ?>
        <?php echo $form->renderHiddenField($model, 'targetFigure') ?>
        <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'lm-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'lm-end')); ?>
        <?php echo $form->renderHiddenField($uomModel, 'id', array('id' => 'uom')) ?>
        <?php echo $form->renderHiddenField($ubtModel, 'startingPeriod', array('id' => 'ubt-start')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'endingPeriod', array('id' => 'ubt-end')) ?>
        <?php echo $form->renderHiddenField($ubtModel, 'id', array('id' => 'ubt')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="leadmeasure-list"></div>
    </div>
</div>


<div id="lm-status">
    <div id="lmStatusContent" style="overflow: hidden">
        <p id="text-status"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>