<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
?>
<script type="text/javascript" src="protected/js/measure-profile/target.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php
        $form = new Form(array(
            'action' => array($model->isNew() ? "measure/insertTargetData" : "measure/updateTargetData"),
            'hasFieldset' => true,
            'class' => 'ink-form'
        ));
        echo $form->startComponent();
        echo $form->constructHeader($model->isNew() ? "Add Target Data" : "Update Target Data");
        ?>
        <div class="ink-alert block info" id="notes">
            <h4>Important Notes</h4>
            <p>
                -&nbsp;Fields with * are required
                <br/>
                -&nbsp;Values added should be equivalent to the Indicator's unit of measure (<?php echo $uom->description; ?>)
            </p>
        </div>
        <?php
        if (isset($notif) && !empty($notif)) {
            $this->renderPartial('commons/_notification', array('notif' => $notif));
        }
        if (isset($validation) && !empty($validation)) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $validation));
        }
        ?>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'dataGroup', array('class' => 'all-25 align-right')) ?>
            <div class="control all-75">
                <?php echo $form->renderTextField($model, 'dataGroup'); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'coveredYear', array('class' => 'all-25 align-right', 'required' => true)) ?>
            <div class="control all-75">
                <?php if ($model->isNew()): ?>
                    <div id="year-input"></div>
                <?php else: echo $form->renderTextField($model, 'coveredYear', array('disabled' => true)); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'value', array('class' => 'all-25 align-right', 'required' => true)) ?>
            <div class="control all-75 append-button">
                <span><?php echo $form->renderTextField($model, 'value'); ?></span>
                <button class="ink-button white flat" disabled="disabled" style="border-top: 1px solid #c6c6c6; border-right: 1px solid #c6c6c6; border-bottom: 1px solid #c6c6c6;">
                    <?php echo strlen($uom->symbol) == 0 ? $uom->description : $uom->symbol; ?>
                </button>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'notes', array('class' => 'all-25 align-right')) ?>
            <div class="control all-75">
                <?php echo $form->renderTextArea($model, 'notes'); ?>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Add', array('class' => 'ink-button flat green', 'style' => 'margin-top: 1em; margin-left:0px;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button flat blue', 'style' => 'margin-top: 1em; margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($baselineReference, 'coveredYear', array('id' => 'start-pointer')); ?>
        <?php echo $form->renderHiddenField($profileModel, 'id', array('id' => 'profile-id')); ?>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'coveredYear', array('id' => 'year')); ?>
        <?php echo $form->renderHiddenField($model, 'validationMode'); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="target-list"></div>
    </div>
</div>

