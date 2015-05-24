<?php

namespace org\csflu\isms\views;

use \org\csflu\isms\util\FormGenerator;
?>
<script type="text/javascript" src="protected/js/measure-profile/index.js"></script>
<div id="profileList-<?php echo $map; ?>"></div>

<div id="timeline-container">
    <div id="content">
        <?php
        $form = new FormGenerator(array(
            'class' => 'ink-form'
        ));
        echo $form->startComponent();
        ?>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>
                <p class="tip" id="tip"></p>
                <?php echo $form->renderSubmitButton('Proceed', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
            <?php echo $form->renderHiddenField('id'); ?>
            <?php echo $form->renderHiddenField('period'); ?>
            <?php echo $form->renderHiddenField('startingPeriod', array(FormGenerator::PROPERTY_VALUE => $startingPeriod)); ?>
            <?php echo $form->renderHiddenField('endingPeriod', array(FormGenerator::PROPERTY_VALUE =>$endingPeriod)); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
