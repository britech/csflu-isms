<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

if(isset($params['baseline']) && !empty($params['baseline'])){
    $baseline = $params['baseline'];
}

$form = new Form(array(
    'action' => array(isset($baseline) ? 'km/updateBaseline' : 'km/insertBaseline'),
    'class' => 'ink-form',
    'hasFieldset'=>true));
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
        }?>
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader((isset($baseline) ? 'Update Baseline Data' : 'Add Baseline Data').'<span style="display: inline-block; float: right; font-weight: normal; font-size: 12px; margin-top: 10px; cursor:pointer;" id="show-dialog" title="Click this text to show the important notes">Show Important Notes</span>');?>
        <div class="ink-alert block info" id="notes">
            <h4>Important Notes<span class="ink-dismiss" style="cursor: pointer" title="Click this icon to close the dialog">&times;</span></h4>
            <p>
                *&nbsp;Values added should be equivalent to the Indicator's unit of measure (<?php echo $params['uom'];?>)
                <br/>
                *&nbsp;For zero figure value, please input "-" to specify the figure value is zero on the year selected.
            </p>
        </div>
        <div id="divider">&nbsp;</div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel('Item Name', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <?php echo $form->renderTextField('Baseline[baselineDataGroup]', array('value'=>  isset($baseline) ? $baseline->baselineDataGroup : '')); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel('Covered Year', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <div id="year"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel('Figure Value', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <?php echo $form->renderTextField('Baseline[value]', array('value'=>  isset($baseline) ? $baseline->value : '')); ?>
                <?php
                if(!isset($baseline)){
                    echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button flat green', 'style' => 'margin-top: 1em; margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php if(isset($baseline)):?>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel('Notes', array('class' => 'all-20 align-right')) ?>
            <div class="control all-80">
                <?php echo $form->renderTextArea('Baseline[notes]', array('value'=>$baseline->notes)); ?>
                <?php echo $form->renderHiddenField('Baseline[id]', array('value'=>$baseline->id))?>
                <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button flat blue', 'style' => 'margin-top: 1em; margin-left:0px;'));?>
            </div>
        </div>
        <?php endif;?>
        <?php echo $form->renderHiddenField('Indicator[id]', array('id' => 'indicator-id', 'value' => $params['indicatorId'])); ?>
        <?php echo $form->renderHiddenField('Baseline[coveredYear]', array('id' => 'yearValue',  'value'=> isset($baseline) ? $baseline->coveredYear : '2010')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="baselineTable" style="margin-bottom: 1em;"></div>
    </div>
</div>

