<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('project/insertComponent'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script src="protected/js/initiative/components.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist Component'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required.
        </div>
        <div id="validation-container" class="ink-alert block">
            <h4>Validation error/s. Please check your entries</h4>
            <p id="validation-content"></p>
        </div>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <label>Component&nbsp;*</label>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'description'); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Phase&nbsp;*</label>
            <div class="control">
                <div id="phase-input"></div>
                <?php echo $form->renderSubmitButton('Enlist', array('class'=>'ink-button green flat', 'style'=>'margin-top:1em; margin-left:0px;'))?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($phaseModel, 'id', array('id' => 'phase')); ?>
        <?php echo $form->renderHiddenField($initiativeModel, 'id', array('id' => 'initiative')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="component-list"></div>
    </div>
</div>
