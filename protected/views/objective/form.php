<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\map\Objective;
use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'objective/insert' : 'objective/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/objective/form.js"></script>
<div class="column-group half-gutters">
    <div class="all-60">        
        <?php
        echo $form->startComponent();
        echo $form->constructHeader($model->isNew() ? 'Add Objective' : 'Update Objective');
        ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['notif']) && !empty($params['notif'])) {
            $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
        }
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'description', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <div id="description-input"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'perspective', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <?php echo $form->renderDropDownList($perspectiveModel, 'id', $perspectives, array('id' => 'pers-id')); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'theme', array('class' => 'all-20 align-right')); ?>
            <div class="control all-80">
                <?php echo $form->renderDropDownList($themeModel, 'id', $themes, array('id' => 'theme-id')); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'environmentStatus', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <?php echo $form->renderDropDownList($model, 'environmentStatus', Objective::getEnvironmentStatus()); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'period', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <div id="periods"></div>
                <?php
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                } else {
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
                }
                ?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'id'); ?>
        <?php echo $form->renderHiddenField($model, 'description', array('id' => 'description')); ?>
        <?php echo $form->renderHiddenField($model, 'startingPeriodDate', array('id' => 'obj-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriodDate', array('id' => 'obj-end')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'startingPeriodDate', array('id' => 'map-start')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'endingPeriodDate', array('id' => 'map-end')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'map-id')); ?>
        <?php echo $form->endComponent(); ?>
    </div>

    <div class="all-40">
        <div id="objectives" style="margin-top: 10px;"></div>
    </div>
</div>

<div id="delete-objective">
    <div id="deleteThemeContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>
