<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'ubt/insertLeadMeasures' : 'ubt/updateLeadMeasure'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
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
        <div class="ink-alert block" id="validation-container">
            <h4>Validation error/s. Please check your entries.</h4>
            <p id="validation-content"></p>
        </div>

        <div class="control-group">
            <label>Lead Measure&nbsp;*</label>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'description'); ?>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top:10px; margin-left:0px;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:10px; margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'leadMeasureEnvironmentStatus'); ?>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'validationMode'); ?>
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