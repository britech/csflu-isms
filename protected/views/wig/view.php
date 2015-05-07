<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
use org\csflu\isms\models\ubt\WigSession;
?>
<script type="text/javascript" src="protected/js/wig/view.js"></script>
<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>

<?php
if ($data->wigMeetingEnvironmentStatus == WigSession::STATUS_OPEN) {
    $file = "wig/_commitments";
} elseif ($data->wigMeetingEnvironmentStatus == WigSession::STATUS_CLOSED){
    $file = "wig/_movement-log";
}
$this->renderPartial($file, $params);
?>

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
            <?php echo $form->renderHiddenField($data, 'id'); ?>
            <?php echo $form->renderHiddenField($data, 'startingPeriod', array('id' => 'wig-start')); ?>
            <?php echo $form->renderHiddenField($data, 'endingPeriod', array('id' => 'wig-end')); ?>
            <?php echo $form->renderHiddenField($ubt, 'startingPeriod', array('id' => 'ubt-start')); ?>
            <?php echo $form->renderHiddenField($ubt, 'endingPeriod', array('id' => 'ubt-end')); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>

<div id="delete-wig">
    <div id="deleteWig" style="overflow: hidden">
        <p id="text">Do you want to delete this WIG Session&nbsp;?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>
