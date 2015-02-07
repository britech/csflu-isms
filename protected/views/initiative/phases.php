<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($phase->isNew() ? 'project/enlistPhase' : 'project/updatePhase'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script src="protected/js/initiative/phases.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($phase->isNew() ? 'Enlist a Phase' : 'Update Phase'); ?>
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
                <?php if ($phase->isNew()): ?>
                    <div id="phaseNumber-input"></div>
                    <?php echo $form->renderHiddenField($phase, 'phaseNumber'); ?>
                <?php else: ?>
                    <?php echo $form->renderTextField($phase, 'phaseNumber', array('readonly' => true)); ?>
                <?php endif; ?>
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

        <?php echo $form->renderHiddenField($phase, 'id'); ?>
        <?php echo $form->renderHiddenField($initiative, 'id', array('id' => 'initiative')); ?>

        <?php
        if ($phase->isNew()) {
            echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left: 0px;'));
        } else {
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:1em; margin-left: 0px;'));
        }
        ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="phase-list"></div>
    </div>
</div>

<div id="delete-phase">
    <div id="deletePhaseContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>