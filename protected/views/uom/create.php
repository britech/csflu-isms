<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$form = new Form(array(
    'action' => array('uom/create'),
    'class' => 'ink-form',
    'hasFieldset' => true
));

echo $form->startComponent();
echo $form->constructHeader('Add UOM');

if(isset($params['validation']) && !empty($params['validation'])):?>
<div style="margin-top: 10px;">
    <?php $this->viewWarningPage('Validation error. Please check your entries', implode('<br/>', $params['validation']));?>
</div>
<?php endif;?>

<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Symbol', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('UnitOfMeasure[symbol]', array('style'=>'width: 150px;'));?>
    </div>
</div>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Description', array('class'=>'all-20 align-right'));?>
    <div class="control all-80">
        <?php echo $form->renderTextField('UnitOfMeasure[description]');?>
        <?php echo $form->renderSubmitButton('Add', array(
            'class'=>'ink-button green flat',
            'style'=>'margin-top: 1em; margin-left: 0px;'
        ));?>
    </div>
</div>
<?php
echo $form->endComponent();
