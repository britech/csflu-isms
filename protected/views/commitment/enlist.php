<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('commitment/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true
));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/commitment/enlist.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist Commitments') ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div class="ink-alert block" id="validation-container">
            <h4>Validation error. Please check your entries</h4>
            <p id="validation-message"></p>
        </div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'commitment', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'commitment', array('id' => 'commitment')); ?>
                <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left:0px;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'commitmentEnvironmentStatus'); ?>
        <?php echo $form->renderHiddenField($user, 'id'); ?>
        <?php echo $form->renderHiddenField($wigSession, 'id'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
