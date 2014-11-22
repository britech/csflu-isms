<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$perspective = $params['perspective'];

$form = new Form(array(
    'action' => array('map/updatePerspective'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<?php echo $form->startComponent(); ?>
<?php echo $form->constructHeader('Update Perspective'); ?>
<?php if (isset($params['validation']) && !empty($params['validation'])) : ?>
    <div style="margin-top:10px;">
        <?php $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation'])); ?>
    </div>    
<?php endif; ?>
<div class="ink-alert basic info">
    <strong>Important Note:</strong>&nbsp;You can only update the Perspective description.
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel('Description&nbsp;*', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80 append-button">
        <span><?php echo $form->renderTextField('Perspective[description]', array('value' => $perspective->description)); ?></span>
        <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat')) ?>
    </div>
    <?php echo $form->renderHiddenField('Perspective[id]', array('value' => $perspective->id)); ?>
    <?php echo $form->renderHiddenField('Perspective[positionOrder]', array('value' => $perspective->positionOrder)); ?>
</div>
<?php
echo $form->endComponent();
