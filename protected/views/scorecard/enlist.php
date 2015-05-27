<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    ModelFormGenerator::PROPERTY_ACTION => array('scorecard/insertMovement'),
    ModelFormGenerator::PROPERTY_CLASS => 'ink-form',
    ModelFormGenerator::PROPERTY_FIELDSETENABLED => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/scorecard/enlist.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist Measure Movement'); ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required
        </div>
        <?php $this->renderPartial('commons/_validation', array('message' => $notif)); ?>
        <div id="validation-container"></div>
        <div class="control-group">
            <?php echo $form->renderLabel($measureProfileModel, 'indicator'); ?>
            <div class="control">
                <?php echo $form->renderTextField($measureProfileModel->indicator, 'description', array(ModelFormGenerator::PROPERTY_READONLY => true)); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Time Period</label>
            <div class="control">
                <input type="text" value="<?php echo $period->format('F Y')?>" readonly/>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($movementModel, 'movementValue', array(ModelFormGenerator::KEY_REQUIRED => true)); ?>
            <div class="control">
                <div id="value-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($movementLogModel, 'notes', array(ModelFormGenerator::KEY_REQUIRED => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($movementLogModel, 'notes'); ?>
                <?php
                echo $form->renderSubmitButton('Enlist', array(
                    ModelFormGenerator::PROPERTY_CLASS => 'ink-button green flat',
                    ModelFormGenerator::PROPERTY_STYLE => 'margin-top: 1em; margin-left: 0px;'
                ))
                ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($measureProfileModel->indicator->uom, 'description', array(ModelFormGenerator::PROPERTY_ID => 'uom')); ?>
        <?php echo $form->renderHiddenField($measureProfileModel, 'id'); ?>
        <?php echo $form->renderHiddenField($movementModel, 'periodDate'); ?>
        <?php echo $form->renderHiddenField($movementModel, 'movementValue'); ?>
        <?php echo $form->renderHiddenField($userModel, 'id', array(ModelFormGenerator::PROPERTY_ID => 'user')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

