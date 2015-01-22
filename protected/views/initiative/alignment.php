<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('alignment/insertInitiativeAlignment'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Link Objectives/Lead Measures'); ?>
        <div class="ink-alert block" id="validation-container">
            <h4 id="validation-header"></h4>
            <p id="validation-content"></p>
        </div>
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group">
            <?php echo $form->renderLabel($initiativeModel, 'objectives'); ?>
            <div class="control">
                <div id="objectives-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($initiativeModel, 'leadMeasures'); ?>
            <div class="control">
                <div id="measures-input"></div>
                <?php echo $form->renderHiddenField($measureModel, 'id', array('id' => 'leadMeasures')); ?>
                <?php echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objectives')); ?>
                <?php echo $form->renderHiddenField($initiativeModel, 'id', array('id' => 'initiative')); ?>
                <?php echo $form->renderSubmitButton('Link', array('class'=>'ink-button green flat', 'style'=>'margin-top: 1em; margin-left: 0px;'))?>
            </div>
        </div>

        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">

    </div>
</div>

