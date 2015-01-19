<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('implementor/link'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script src="protected/js/initiative/implem-offices.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Add Implementing Offices'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required
        </div>
        <div class="ink-alert block" id="validation-container">
            <h4 id="validation-header"></h4>
            <p id="validation-content"></p>
        </div>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'department', array('required' => true)); ?>
            <div class="control">
                <div id="offices-input"></div>
                <?php echo $form->renderHiddenField($model, 'designation'); ?>
                <?php echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'offices')); ?>
                <?php echo $form->renderHiddenField($initiativeModel, 'id', array('id' => 'initiative')); ?>
                <?php echo $form->renderSubmitButton('Add', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="implem-list"></div>
    </div>
</div>