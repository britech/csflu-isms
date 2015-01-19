<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('implementor/link'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script src="protected/js/initiative/implem-offices.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Add Implementing Offices'); ?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'department', array('required' => true)); ?>
            <div class="control">
                <div id="offices-input"></div>
                <?php echo $form->renderHiddenField($model, 'designation'); ?>
                <?php echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'offices')); ?>
                <?php echo $form->renderHiddenField($initiativeModel, 'id', array('id' => 'initiative')); ?>
                <?php echo $form->renderSubmitButton('Add', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="implem-list"></div>
    </div>
</div>