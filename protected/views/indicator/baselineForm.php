<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array(!$model->isNew() ? 'indicator/updateBaseline' : 'indicator/insertBaseline'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script src="protected/js/indicator/baselineForm.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader(!$model->isNew() ? 'Update Baseline Data' : 'Add Baseline Data', array('style' => 'margin-bottom: 10px;')); ?>
        <div class="ink-alert block info" id="notes">
            <h4>Important Notes</h4>
            <p>
                -&nbsp;Fields with * are required
                <br/>
                -&nbsp;Values added should be equivalent to the Indicator's unit of measure (<?php echo $uom; ?>)
            </p>
        </div>
        <?php
        if (isset($notif) && !empty($notif)) {
            $this->renderPartial('commons/_notification', array('notif' => $notif));
        }
        if (isset($validation) && !empty($validation)) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $validation));
        }
        ?>
        <div id="validation-container"></div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'baselineDataGroup', array('class' => 'all-25 align-right')) ?>
            <div class="control all-75">
                <?php echo $form->renderTextField($model, 'baselineDataGroup'); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'coveredYear', array('class' => 'all-25 align-right', 'required' => true)) ?>
            <div class="control all-75">
                <?php if ($model->isNew()): ?>
                    <div id="year"></div>
                <?php else: echo $form->renderTextField($model, 'coveredYear', array('disabled' => true)); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'value', array('class' => 'all-25 align-right', 'required' => true)) ?>
            <div class="control all-75">
                <?php echo $form->renderTextField($model, 'value'); ?>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button flat green', 'style' => 'margin-top: 1em; margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php if (!$model->isNew()): ?>
            <div class="control-group column-group half-gutters">
                <?php echo $form->renderLabel($model, 'notes', array('class' => 'all-25 align-right')) ?>
                <div class="control all-75">
                    <?php echo $form->renderTextArea($model, 'notes'); ?>
                    <?php echo $form->renderHiddenField($model, 'id'); ?>
                    <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button flat blue', 'style' => 'margin-top: 1em; margin-left:0px;')); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php echo $form->renderHiddenField($indicatorModel, 'id', array('id' => 'indicator-id')); ?>
        <?php echo $form->renderHiddenField($model, 'coveredYear', array('id' => 'yearValue')); ?>
        <?php echo $form->renderHiddenField($model, 'validationMode'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="baselineTable" style="margin-bottom: 1em;"></div>
    </div>
</div>

<div id="delete-baseline">
    <div id="deleteBaselineContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>
