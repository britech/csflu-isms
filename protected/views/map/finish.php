<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('map/updateEnvironmentStatus'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script type="text/javascript" src="protected/js/map/finish.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong> Fields with * are required.
        </div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <?php echo $form->startComponent(); ?>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'strategyEnvironmentStatus', array('class' => 'all-35 align-right', 'required' => true)); ?>
            <div class="control all-65">
                <?php echo $form->renderDropDownList($model, 'strategyEnvironmentStatus', $status); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters" id="implem-date">
            <?php echo $form->renderLabel($model, 'implementationDate', array('class' => 'all-35 align-right', 'required' => true)); ?>
            <div class="control all-65">
                <div id="implem-date-input"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters" id="term-date">
            <?php echo $form->renderLabel($model, 'terminationDate', array('class' => 'all-35 align-right', 'required' => true, 'id' => 'term-label')); ?>
            <div class="control all-65">
                <div id="term-date-input"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <label class="all-35 align-right">&nbsp;</label>
            <div class="control all-65">
                <?php echo $form->renderSubmitButton('Finish', array('class' => 'ink-button green flat', 'style' => 'margin-left:0px;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'startingPeriodDate'); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriodDate'); ?>
        <?php echo $form->renderHiddenField($model, 'implementationDate'); ?>
        <?php echo $form->renderHiddenField($model, 'terminationDate'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>