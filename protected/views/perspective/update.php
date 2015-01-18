<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('perspective/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/perspective/update.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50 push-center">
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
            <?php echo $form->renderLabel($model, 'description', array('class' => 'all-20 align-right')); ?>
            <div class="control all-80">
                <div id="description-input"></div>
                <?php
                echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat',
                    'style' => 'margin-top: 10px; margin-left: 0px;'));
                ?>
            </div>
            <?php echo $form->renderHiddenField($model, 'description', array('id' => 'description')); ?>
            <?php echo $form->renderHiddenField($model, 'id'); ?>
            <?php echo $form->renderHiddenField($model, 'positionOrder'); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

