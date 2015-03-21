<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('wig/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/wig/manage.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist WIG Session'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div id="validation-container" class="ink-alert block">
            <h4>Validation error. Please check your entries.</h4>
            <p>Timeline should be defined</p>
        </div>
        <?php
        if (isset($params['validation']) and !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        <div class="control-group">
            <?php echo $form->renderLabel($ubtModel, 'description'); ?>
            <div class="control">
                <?php echo $form->renderTextField($ubtModel, 'description', array('readonly' => true)); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline of WIG Session&nbsp*</label>
            <div class="control">
                <div id="timeline"></div>
                <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left:0px;')) ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'wig-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'wig-end')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'startingPeriod', array('id' => 'ubt-start')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'endingPeriod', array('id' => 'ubt-end')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'id', array('id' => 'ubt')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="wig-list"></div>
    </div>
</div>

<div id="delete-wig">
    <div id="deleteWig" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>

