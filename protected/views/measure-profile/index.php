<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
?>
<script type="text/javascript" src="protected/js/measure-profile/index.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-45">
        <?php
        $form = new Form(array(
            'action' => array('measure/insert'),
            'class' => 'ink-form',
            'hasFieldset' => true
        ));
        echo $form->startComponent();
        echo $form->constructHeader($model->isNew() ? "Add Measure Profile" : "Update Measure Profile");
        ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
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
        <?php echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objective')); ?>
        <?php echo $form->renderHiddenField($indicatorModel, 'id', array('id' => 'indicator')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
        <?php
        if ($model->isNew()) {
            echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top:10px; margin-left:0px;'));
        } else {
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:10px; margin-left:0px;'));
        }
        ?>
        <?php echo $form->endComponent(); ?>
    </div>

    <div class="all-55">
        <div id="profileList"></div>
    </div>
</div>
