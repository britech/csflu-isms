<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\models\map\StrategyMap;

$form = new Form(array(
    'action' => array('map/updateEnvironmentStatus'),
    'class' => 'ink-form',
    'hasFieldset' => true));
?>
<script type="text/javascript" src="protected/js/map/finish.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-60 push-center">
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong> Fields with * are required.
        </div>
        <?php
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <?php echo $form->startComponent(); ?>
        <?php if ($model->strategyEnvironmentStatus != StrategyMap::STATUS_INACTIVE): ?>
            <div class="control-group column-group half-gutters">
                <?php echo $form->renderLabel('Status', array('class' => 'all-35 align-right', 'required' => true)); ?>
                <div class="control all-65">
                    <?php echo $form->renderDropDownList('StrategyMap[strategyEnvironmentStatus]', $status, array('value' => $model->strategyEnvironmentStatus)); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="control-group column-group half-gutters" id="implem-date">
            <?php echo $form->renderLabel('Date Implemented', array('class' => 'all-35 align-right', 'required' => true)); ?>
            <div class="control all-65">
                <div id="implem-date-input"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters" id="term-date">
            <?php echo $form->renderLabel(' ', array('class' => 'all-35 align-right', 'required' => true, 'id' => 'term-label')); ?>
            <div class="control all-65">
                <div id="term-date-input"></div>
            </div>
        </div>
        <div class="control-group column-group half-gutters">
            <label class="all-35 align-right">&nbsp;</label>
            <div class="control all-65">
                <?php 
                if($model->strategyEnvironmentStatus == StrategyMap::STATUS_DRAFT){
                     echo $form->renderSubmitButton('Finish', array('class' => 'ink-button green flat', 'style' => 'margin-left:0px;'));
                } else {
                     echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-left:0px;'));
                }
                ?>
            </div>
        </div>
        <?php
            if($model->strategyEnvironmentStatus == StrategyMap::STATUS_INACTIVE && $status == StrategyMap::STATUS_ACTIVE){
                echo $form->renderHiddenField('StrategyMap[strategyEnvironmentStatus]', array('value'=>$status));
            }
        ?>
        <?php echo $form->renderHiddenField('StrategyMap[id]', array('value' => $model->id)); ?>
        <?php echo $form->renderHiddenField('StrategyMap[startingPeriodDate]', array('value' => $model->startingPeriodDate)); ?>
        <?php echo $form->renderHiddenField('StrategyMap[endingPeriodDate]', array('value'=>$model->endingPeriodDate)); ?>
        <?php echo $form->renderHiddenField('StrategyMap[implementationDate]', array('value'=>$model->implementationDate)); ?>
        <?php echo $form->renderHiddenField('StrategyMap[terminationDate]', array('value'=>$model->terminationDate)); ?>
        <?php echo $form->endComponent(); ?>
    </div>
</div>