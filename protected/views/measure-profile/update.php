<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
?>
<script type="text/javascript" src="protected/js/measure-profile/update.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50 push-center">
        <?php
        $form = new Form(array(
            'action' => array('measure/update'),
            'class' => 'ink-form',
            'hasFieldset' => true
        ));
        echo $form->startComponent();
        echo $form->constructHeader("Update Measure Profile");
        ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'objective', array('required' => true)); ?>
            <div class="control">
                <div id="objective-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'indicator', array('required' => true)); ?>
            <div class="control">
                <div id="indicator-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'frequencyOfMeasure', array('required' => true)); ?>
            <ul class="control unstyled inline" style="margin-top: 0px; margin-bottom: 0px;">
                <?php
                foreach ($frequencyTypes as $frequencyValue => $frequencyType) {
                    echo '<li>' . $form->renderCheckBox($model, 'frequencyOfMeasure', $frequencyType, array('value' => $frequencyValue)) . '</li>';
                }
                ?>
            </ul>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'measureType', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'measureType', $measureTypes); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'measureProfileEnvironmentStatus', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'measureProfileEnvironmentStatus', $statusTypes); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'periods', array('required' => true)); ?>
            <div class="control">
                <div id="periods"></div>
            </div>
        </div>
        <?php echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objective')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
        <?php echo $form->renderHiddenField($model, 'timelineStart'); ?>
        <?php echo $form->renderHiddenField($model, 'timelineEnd'); ?>
        <?php echo $form->renderHiddenField($indicatorModel, 'id', array('id' => 'indicator')); ?>
        <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:10px; margin-left:0px;')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
