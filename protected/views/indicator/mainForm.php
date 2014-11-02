<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\models\indicator\Indicator;

if (isset($params['indicator']) && !empty($params['indicator'])) {
    $indicator = $params['indicator'];
}

$form = new Form(array(
    'action' => array(isset($indicator) ? 'km/update' : 'km/create'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));

echo $form->startComponent();
echo $form->constructHeader(isset($indicator) ? 'Update Indicator Data' : 'Enlist an Indicator');
?>
<script type="text/javascript" src="protected/js/indicator/mainForm.js"></script>
<?php if (isset($params['validation']) && !empty($params['validation'])): ?>
    <div style="margin-top: 10px;">
        <?php $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation'])); ?>
    </div>
<?php endif; ?>
<div style="margin-top: 5px;">&nbsp;</div>

<div class="control-group">
    <?php echo $form->renderLabel('Description', array()); ?>
    <div class="control">
        <?php echo $form->renderTextArea('Indicator[description]', array('value' => isset($indicator) ? $indicator->description : '')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Rationale', array()); ?>
    <div class="control">
        <?php echo $form->renderTextArea('Indicator[rationale]', array('value' => isset($indicator) ? $indicator->rationale : '')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Formula Description', array()); ?>
    <div class="control">
        <?php echo $form->renderTextArea('Indicator[formula]', array('value' => isset($indicator) ? $indicator->formula : '')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Source of Data', array()); ?>
    <div class="control">
        <?php echo $form->renderTextField('Indicator[dataSource]', array('value' => isset($indicator) ? $indicator->dataSource : '')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Status - Source of Data', array()); ?>
    <div class="control">
        <?php echo $form->renderDropDownList('Indicator[dataSourceStatus]', Indicator::getDataSourceDescriptionList(), array('value' => isset($indicator) ? $indicator->dataSourceStatus : '')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Date of Availability - Source of Data', array()); ?>
    <div class="control">
        <?php echo $form->renderTextField('Indicator[dataSourceAvailabilityDate]', array('value' => isset($indicator) ? $indicator->dataSourceAvailabilityDate : '')) ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Unit of Measure', array()); ?>
    <div class="control">
        <div id="uomList"></div>
        <?php echo $form->renderHiddenField('UnitOfMeasure[id]', array('id' => 'uom-id', 'value'=>isset($indicator) ? $indicator->uom->id : '')); ?>
        <?php
        if (isset($indicator)) {
            echo $form->renderHiddenField('Indicator[id]', array('value'=>$indicator->id));
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        } else {
            echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        }
        ?>
    </div>
</div>
<?php
echo $form->endComponent();
