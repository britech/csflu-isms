<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\map\Objective;
use org\csflu\isms\util\ModelFormGenerator as Form;

$model = $params['model'];
$mapModel = $params['mapModel'];
$perspectiveModel = $params['perspectiveModel'];
$themeModel = $params['themeModel'];
$perspectives = $params['perspectives'];
$themes = $params['themes'];

$form = new Form(array(
    'action' => array('map/insertObjective'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/objective/map-insert.js"></script>
<div class="column-group half-gutters">
    <div class="all-60">        
        <?php
        if (isset($params['notif']) && !empty($params['notif'])) {
            $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
        }
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }

        echo $form->startComponent();
        echo $form->constructHeader('Add Objective')
        ?>
        <div class="ink-alert basic info">
            <strong>Important Note:</strong>&nbsp;Fields with * are required.
        </div>
        <div id="validation-container"></div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'description', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <?php echo $form->renderTextField($model, 'description'); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'perspective', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <?php echo $form->renderDropDownList($perspectiveModel, 'id', $perspectives, array('id'=>'pers-id')); ?>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <?php echo $form->renderLabel($model, 'theme', array('class' => 'all-20 align-right', 'required' => true)); ?>
            <div class="control all-80">
                <?php echo $form->renderDropDownList($themeModel, 'id', $themes, array('id'=>'theme-id')); ?>
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
                <?php echo $form->renderSubmitButton('Create', array('class'=>'ink-button green flat', 'style'=>'margin-top: 1em; margin-left: 0px;'));?>
            </div>
        </div>
        <?php echo $form->renderHiddenField($model, 'startingPeriodDate', array('id' => 'obj-start')); ?>
        <?php echo $form->renderHiddenField($model, 'endingPeriodDate', array('id' => 'obj-end')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'startingPeriodDate', array('id' => 'map-start')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'endingPeriodDate', array('id' => 'map-end')); ?>
        <?php echo $form->renderHiddenField($mapModel, 'id'); ?>
        <?php echo $form->endComponent(); ?>
    </div>

    <div class="all-40">
        <div id="objectives" style="margin-top: 10px;"></div>
    </div>
</div>







