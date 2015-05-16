<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator;
?>
<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
<script type="text/javascript" src="protected/js/initiative/manage.js"></script>
<div id="initiativeList-<?php echo $department->id; ?>"></div>

<div id="timeline-container">
    <div id="content">
        <?php
        $form = new FormGenerator(array(
            'class' => 'ink-form',
            'method' => FormGenerator::METHOD_GET
        ));
        echo $form->startComponent();
        ?>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>
                <p class="tip" id="tip"></p>
                <?php echo $form->renderSubmitButton('Filter Activities', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
            <?php echo $form->renderHiddenField('id', array('id' => 'initiative')); ?>
            <?php echo $form->renderHiddenField('startingPeriod'); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>