<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
?>
<script type="text/javascript" src="protected/js/wig/view.js"></script>
<div class="column-group">
    <div class="all-20">
        <?php $this->renderPartial('wig/_view-navi', $params); ?>
    </div>
    <div class="all-80">
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
    </div>
</div>


<div id="timeline-prompt">
    <div id="timelinePromptContent" style="overflow: hidden">
        <?php
        $form = new Form(array(
            'action' => array('wig/update'),
            'class' => 'ink-form'
        ));

        echo $form->startComponent();
        ?>
        <div class="control-group">
            <label style="font-weight: bold">Timeline&nbsp*</label>
            <div class="control">
                <div id="timeline-input"></div>
                <?php echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:1em; margin-left:0px;')) ?>
            </div>
            <?php echo $form->renderHiddenField($data, 'id');?>
            <?php echo $form->renderHiddenField($data, 'startingPeriod', array('id' => 'wig-start')); ?>
            <?php echo $form->renderHiddenField($data, 'endingPeriod', array('id' => 'wig-end')); ?>
            <?php echo $form->renderHiddenField($ubt, 'startingPeriod', array('id' => 'ubt-start')); ?>
            <?php echo $form->renderHiddenField($ubt, 'endingPeriod', array('id' => 'ubt-end')); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

