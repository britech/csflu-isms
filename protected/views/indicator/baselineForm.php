<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array(!$model->isNew() ? 'indicator/updateBaseline' : 'indicator/insertBaseline'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script src="protected/js/indicator/baselineForm.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php
        if (isset($params['notif']) && !empty($params['notif'])) {
            $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
        }

        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader(
                (isset($baseline) ? 'Update Baseline Data' : 'Add Baseline Data') . '<span style="display: inline-block; float: right; font-weight: normal; font-size: 12px; margin-top: 10px; cursor:pointer;" id="show-dialog" title="Click this text to show the important notes">Show Important Notes</span>', 
                array('style'=>'margin-bottom: 10px;')); ?>
        <div class="ink-alert block info" id="notes">
            <h4>Important Notes<span class="ink-dismiss" style="cursor: pointer" title="Click this icon to close the dialog">&times;</span></h4>
            <p>
                *&nbsp;Values added should be equivalent to the Indicator's unit of measure (<?php echo $params['uom']; ?>)
                <br/>
                *&nbsp;For zero figure value, please input "-" to specify the figure value is zero on the year selected.
            </p>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'baselineDataGroup', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <?php echo $form->renderTextField($model, 'baselineDataGroup'); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'coveredYear', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <div id="year"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'value', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <?php echo $form->renderTextField($model, 'value'); ?>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button flat green', 'style' => 'margin-top: 1em; margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php if (!$model->isNew()): ?>
            <div class="control-group column-group half-gutters">
                <?php echo $form->renderLabel($model, 'notes', array('class' => 'all-20 align-right')) ?>
                <div class="control all-80">
                    <?php echo $form->renderTextArea($model, 'notes'); ?>
                    <?php echo $form->renderHiddenField($model, 'id'); ?>
                    <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button flat blue', 'style' => 'margin-top: 1em; margin-left:0px;')); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php echo $form->renderHiddenField($indicatorModel, 'id', array('id' => 'indicator-id')); ?>
        <?php echo $form->renderHiddenField($model, 'coveredYear', array('id' => 'yearValue')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="baselineTable" style="margin-bottom: 1em;"></div>
    </div>
</div>

