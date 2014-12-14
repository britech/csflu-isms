<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array(!$model->isNew() ? 'indicator/update' : 'indicator/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));

echo $form->startComponent();
echo $form->constructHeader(!$model->isNew() ? 'Update Indicator Data' : 'Enlist an Indicator');
?>
<script type="text/javascript" src="protected/js/indicator/mainForm.js"></script>
<div class="ink-alert basic info"><strong>Important Note:</strong>&nbsp;Fields with * are required.</div>
<div id="validation-container"></div>
<?php if (isset($params['validation']) && !empty($params['validation'])): ?>
    <div style="margin-bottom: 10px;">
        <?php $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation'])); ?>
    </div>
<?php endif; ?>
<div class="control-group">
    <?php echo $form->renderLabel($model, 'description', array('required' => true)); ?>
    <div class="control">
        <?php echo $form->renderTextArea($model, 'description'); ?>
        <p class="tip">What is the indicator?</p>
    </div>
</div>

<?php if (!$model->isNew()): ?>
    <div class="control-group">
        <?php echo $form->renderLabel($model, 'rationale', array('required' => true)); ?>
        <div class="control">
            <?php echo $form->renderTextArea($model, 'rationale'); ?>
            <p class="tip">What is the reason behind choosing this measure?</p>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->renderLabel($model, 'formula', array('required' => true)); ?>
        <div class="control">
            <?php echo $form->renderTextArea($model, 'formula'); ?>
            <p class="tip">How is the measure calculated? Clarify in terms of formula.</p>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->renderLabel($model, 'dataSource', array('required' => true)); ?>
        <div class="control">
            <?php echo $form->renderTextArea($model, 'dataSource'); ?>
            <p class="tip">What data is required in calculating the measure? Where/how is it acquired?</p>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->renderLabel($model, 'dataSourceStatus', array('required' => true)); ?>
        <div class="control">
            <?php echo $form->renderTextArea($model, 'dataSourceStatus'); ?>
            <p class="tip">Is information about the measure available?</p>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->renderLabel($model, 'dataSourceAvailabilityDate', array('required' => true)); ?>
        <div class="control">
            <?php echo $form->renderTextArea($model, 'dataSourceAvailabilityDate'); ?>
            <p class="tip">When will this info be available?</p>
        </div>
    </div>
<?php endif; ?>
<div class="control-group">
    <?php echo $form->renderLabel($uomModel, 'description', array('required' => true)); ?>
    <div class="control">
        <div id="uomList"></div>
        <p class="tip">What is the unit of measure used?</p>
        <?php echo $form->renderHiddenField($uomModel, 'id', array('id' => 'uom-id')); ?>
        <?php
        if (!$model->isNew()) {
            echo $form->renderHiddenField($model, 'id');
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        } else {
            echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        }
        ?>
    </div>
</div>
<?php
echo $form->endComponent();
