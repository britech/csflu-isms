<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\models\map\StrategyMap;

$form = new Form(array(
    'action' => array($model->isNew() ? 'map/insert' : 'map/update'),
    'class' => 'ink-form',
    'hasFieldset' => true,
    'style' => 'margin-bottom: 10px'
        ));
echo $form->startComponent();
echo $form->constructHeader(empty($model) ? 'Create a Strategy Map - Initial Phase' : 'Update Entry Data');
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/map/create.js"></script>
<div class="ink-alert basic info">Fields with * are required.</div>
<div id="validation-container"></div>
<?php
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<?php if($model->strategyEnvironmentStatus == StrategyMap::STATUS_DRAFT):?>
<div class="control-group">
    <?php echo $form->renderLabel('Vision Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[visionStatement]', array('value' => empty($model) ? '' : $model->visionStatement)); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Mission Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[missionStatement]', array('value' => empty($model) ? '' : $model->missionStatement)); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Values Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[valuesStatement]', array('value' => empty($model) ? '' : $model->valuesStatement)); ?>
    </div>
</div>
<?php endif;?>
<div class="column-group quarter-gutters">
    <?php if($model->strategyEnvironmentStatus == StrategyMap::STATUS_DRAFT):?>
    <div class="all-33">
        <div class="control-group">
            <?php echo $form->renderLabel('Strategy Type&nbsp*'); ?>
            <div class="control">
                <?php echo $form->renderDropDownList('StrategyMap[strategyType]', StrategyMap::getStrategyTypes(), array('value' => empty($model) ? '' : $model->strategyType)); ?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <div class="<?php echo $model->strategyEnvironmentStatus == StrategyMap::STATUS_DRAFT ? "all-33" : "all-50"?>">
        <div class="control-group">
            <?php echo $form->renderLabel('Starting and Ending Periods&nbsp*'); ?>
            <div class="control">
                <div id="periodDate"></div>
                <?php echo $form->renderHiddenField('StrategyMap[startingPeriodDate]', array('value' => empty($model) ? '' : $model->startingPeriodDate)); ?>
                <?php echo $form->renderHiddenField('StrategyMap[endingPeriodDate]', array('value' => empty($model) ? '' : $model->endingPeriodDate)); ?>
                <?php echo $form->renderHiddenField('StrategyMap[name]', array('value' => empty($model) ? '' : $model->name)); ?>
            </div>
        </div>
    </div>

    <div class="<?php echo $model->strategyEnvironmentStatus == StrategyMap::STATUS_DRAFT ? "all-33" : "all-50"?>">
        <div class="control-group">
            <?php echo $form->renderLabel('&nbsp;'); ?>
            <div class="control">
                <?php
                echo $form->renderHiddenField('mode', array('value' => empty($model) ? 1 : 2));
                if ($model->isNew()) {
                    echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat',
                        'style' => 'width: 100%; margin-left: 0px;'));
                } else {
                    echo $form->renderHiddenField('StrategyMap[id]', array('value' => $model->id));
                    echo $form->renderHiddenField('StrategyMap[strategyEnvironmentStatus]', array('value'=>$model->strategyEnvironmentStatus));
                    echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat',
                        'style' => 'width: 100%; margin-left: 0px;'));
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $form->endComponent();
