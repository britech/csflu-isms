<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
use org\csflu\isms\models\map\StrategyMap;

$form = new Form(array(
    'action' => array($model->isNew() ? 'map/insert' : 'map/update'),
    'class' => 'ink-form',
    'hasFieldset' => true,
    'style' => 'margin-bottom: 10px'
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/map/create.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($model->isNew() ? 'Create a Strategy Map' : 'Update Entry Data'); ?>
        <div class="ink-alert basic info">Fields with * are required.</div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'visionStatement', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'visionStatement'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'missionStatement', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'missionStatement'); ?>
                <?php // echo $form->renderTextArea($model, 'missionStatement', array('id' => 'mission')); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'valuesStatement', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'valuesStatement', array('id' => 'values')); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'strategyType', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'strategyType', $strategyTypes); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline</label>
            <div class="control">
                <div id="periodDate"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'strategyEnvironmentStatus', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'strategyEnvironmentStatus', $statusTypes); ?>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                }
                ?>
                <?php echo $form->renderHiddenField($model, 'startingPeriodDate'); ?>
                <?php echo $form->renderHiddenField($model, 'endingPeriodDate'); ?>
                <?php echo $form->renderHiddenField($model, 'name'); ?>
                <?php echo $form->renderHiddenField($model, 'id'); ?>
            </div>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
