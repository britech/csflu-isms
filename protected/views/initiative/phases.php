<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('project/enlistPhase'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script src="protected/js/initiative/phases.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist a Phase'); ?>
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
            <?php echo $form->renderLabel($phase, 'phaseNumber', array('required' => true)); ?>
            <div class="control">
                <div id="phaseNumber-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($phase, 'title', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($phase, 'title'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($phase, 'description', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($phase, 'description'); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($phase, 'validationMode'); ?>
        <?php echo $form->renderHiddenField($phase, 'phaseNumber'); ?>
        <?php echo $form->renderHiddenField($initiative, 'id', array('id' => 'initiative')); ?>
        <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left: 0px;')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="phase-list"></div>
    </div>
</div>