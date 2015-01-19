<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'initiative/insert' : 'initiative/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/initiative/profile.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader($model->isNew() ? "Create an Initiative" : "Update Initiative"); ?>
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
            <?php echo $form->renderLabel($model, 'title', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'title'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'description', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'description'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'beneficiaries', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'beneficiaries'); ?>
            </div>
        </div>
        <?php if ($model->isNew()): ?>
            <div class="control-group">
                <?php echo $form->renderLabel($model, 'objectives', array('required' => true)); ?>
                <div class="control">
                    <div id="objectives-input"></div>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form->renderLabel($model, 'leadMeasures', array('required' => true)); ?>
                <div class="control">
                    <div id="measures-input"></div>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form->renderLabel($model, 'implementingOffices', array('required' => true)); ?>
                <div class="control">
                    <div id="offices-input"></div>
                </div>
            </div>
        <?php endif; ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'eoNumber'); ?>
            <div class="control">
                <?php echo $form->renderTextField($model, 'eoNumber'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'advisers'); ?>
            <div class="control">
                <?php echo $form->renderTextArea($model, 'advisers'); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'initiativeEnvironmentStatus', array('required' => true)); ?>
            <div class="control">
                <?php echo $form->renderDropDownList($model, 'initiativeEnvironmentStatus', $statusTypes); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline</label>
            <div class="control">
                <div id="timeline-input"></div>
                <?php
                if ($model->isNew()) {
                    echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objectives'));
                    echo $form->renderHiddenField($measureModel, 'id', array('id' => 'measures'));
                    echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'offices'));
                }
                ?>
                <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
                <?php echo $form->renderHiddenField($mapModel, 'startingPeriodDate', array('id' => 'map-start')); ?>
                <?php echo $form->renderHiddenField($mapModel, 'endingPeriodDate', array('id' => 'map-end')); ?>
                <?php echo $form->renderHiddenField($model, 'validationMode'); ?>
                <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'start')); ?>
                <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'end')); ?>
                <?php echo $form->renderHiddenField($model, 'id'); ?>

                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                }
                ?>
            </div>
        </div>
<?php echo $form->endComponent(); ?>
    </div>
</div>