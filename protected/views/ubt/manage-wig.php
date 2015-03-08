<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('wig/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/ubt/manage-wig.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Enlist WIG Session'); ?>
        <div class="control-group">
            <?php echo $form->renderLabel($ubtModel, 'description'); ?>
            <div class="control">
                <?php echo $form->renderTextField($ubtModel, 'description', array('readonly' => true)); ?>
            </div>
        </div>
        <div class="control-group">
            <label>Timeline&nbsp*</label>
            <div class="control">
                <div id="timeline"></div>
                <?php echo $form->renderSubmitButton('Enlist', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left:0px;')) ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'startingPeriod', array('id' => 'wig-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriod', array('id' => 'wig-end')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'startingPeriod', array('id' => 'ubt-start')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'endingPeriod', array('id' => 'ubt-end')); ?>
        <?php echo $form->renderHiddenField($ubtModel, 'id', array('id' => 'ubt')); ?>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="wig-list"></div>
    </div>
</div>

