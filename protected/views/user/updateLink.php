<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('user/linkUpdate'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script src="protected/js/user/updateLink.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader("Update Linked Security Role") ?>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'securityRole', array('class' => 'all-20 align-right')); ?>
            <div class="control all-80">
                <div id="securityRole-list"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model->employee, 'position', array('class' => 'all-20 align-right')); ?>
            <div class="control all-80">
                <div id="position-list"></div>
                <?php echo $form->renderSubmitButton('Update Link', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model->employee, 'id'); ?>
        <?php echo $form->renderHiddenField($model->securityRole, 'id', array('id' => 'securityRole-id')); ?>
        <?php echo $form->renderHiddenField($model->employee->position, 'id', array('id' => 'position-id')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

