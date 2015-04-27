<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('ip/generateReport'),
    'class' => 'ink-form'
        ));
?>
<script type="text/javascript" src="protected/js/ip/report.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <div class="ink-alert basic info" style="margin-top: 0px;">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'user', array('required' => true)); ?>
            <div class="control">
                <input type="text" readonly="readonly" value="<?php echo $employee; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>                
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'unitBreakthrough', array('required' => true)) ?>
            <div class="control">
                <div id="ubt-input"></div>
                <?php echo $form->renderSubmitButton('Generate Report', array('class' => 'ink-button blue flat', 'style' => 'margin-top:1em; margin-left:0px; ')) ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model->user, 'id', array('id' => 'user')); ?>
        <?php echo $form->renderHiddenField($model->user->employee->department, 'id', array('id' => 'department')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'id', array('id' => 'ubt')); ?>
        <?php echo $form->renderHiddenField($model, 'startingPeriod'); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

