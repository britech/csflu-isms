<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('commitment/updateEntry'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/commitment/_entry.js"></script>
<?php echo $form->startComponent(); ?>
<?php echo $form->constructHeader('Update Commitment Entry'); ?>
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
    <?php echo $form->renderLabel($model, 'commitment'); ?>
    <div class="control">
        <?php echo $form->renderTextField($model, 'commitment', array('id' => 'commitment')); ?>
        <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;')) ?>
    </div>
</div>
<?php echo $form->renderHiddenField($model, 'id'); ?>
<?php echo $form->renderHiddenField($model, 'commitmentEnvironmentStatus'); ?>
<?php echo $form->renderHiddenField($model->user, 'id', array('id' => 'user')); ?>
<?php echo $form->endComponent(); ?>

<div id="dialog-pending">
    <div id="dialogPendingContent" style="overflow: hidden">
        <p id="text-pending"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-pending">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>

<div id="dialog-delete">
    <div id="dialogDeleteContent" style="overflow: hidden">
        <p id="text-delete"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-delete">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>