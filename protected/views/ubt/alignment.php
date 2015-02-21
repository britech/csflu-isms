<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array('alignment/insertUbtAlignment'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/ubt/alignment.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Link Objectives/Lead Measures', array('style' => 'margin-bottom: 10px;')); ?>
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
            <?php echo $form->renderLabel($model, 'objectives'); ?>
            <div class="control">
                <div id="objectives-input"></div>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'leadMeasures'); ?>
            <div class="control">
                <div id="measures-input"></div>
                <?php echo $form->renderHiddenField($measureModel, 'id', array('id' => 'measures')); ?>
                <?php echo $form->renderHiddenField($objectiveModel, 'id', array('id' => 'objectives')); ?>
                <?php echo $form->renderHiddenField($model, 'id', array('id' => 'ubt')); ?>
                <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map')); ?>
                <?php echo $form->renderSubmitButton('Link', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;')) ?>
            </div>
        </div>

        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="objectives-list"></div>
        <div id="measures-list" style="margin-top: 20px;"></div>
    </div>
</div>

<div id="delete-objective">
    <div id="deleteObjectiveContent" style="overflow: hidden">
        <p id="text-objective"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-objective">Yes</button>
            <button class="ink-button green flat" id="deny-objective">No</button>
        </div>
    </div>
</div>

<div id="delete-measure">
    <div id="deleteMeasureContent" style="overflow: hidden">
        <p id="text-measure"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-measure">Yes</button>
            <button class="ink-button green flat" id="deny-measure">No</button>
        </div>
    </div>
</div>

