<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;
?>
<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
<script type="text/javascript" src="protected/js/initiative/manage.js"></script>
<div id="initiativeList-<?php echo $department->id; ?>"></div>

<div id="timeline-container">
    <div id="content">
        <?php
        $form = new ModelFormGenerator(array(
            'action' => array('project/listActivities'),
            'class' => 'ink-form'
        ));
        echo $form->startComponent();
        ?>
        <div class="control-group">
            <label>Timeline&nbsp;*</label>
            <div class="control">
                <div id="timeline-input"></div>
                <?php echo $form->renderSubmitButton('Filter Activities', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')); ?>
            </div>
            <?php echo $form->renderHiddenField($model, 'id', array('id' => 'initiative')); ?>
            <?php echo $form->renderHiddenField($model, 'startingPeriod'); ?>
            <?php echo $form->renderHiddenField($model, 'endingPeriod'); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>