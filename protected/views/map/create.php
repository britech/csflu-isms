<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\models\map\StrategyMap;

$form = new Form(array(
    'action' => array('map/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true,
    'style' => 'margin-bottom: 10px'
        ));

echo $form->startComponent();
echo $form->constructHeader('Create a Strategy Map - Initial Phase');
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/map/create.js"></script>
<div class="ink-alert basic info">Fields with * are required.</div>
<?php
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<div class="control-group">
    <?php echo $form->renderLabel('Vision Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[visionStatement]'); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Mission Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[missionStatement]'); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->renderLabel('Values Statement&nbsp*'); ?>
    <div class="control">
        <?php echo $form->renderTextArea('StrategyMap[valuesStatement]'); ?>
    </div>
</div>
<div class="column-group quarter-gutters">
    <div class="all-33">
        <div class="control-group">
            <?php echo $form->renderLabel('Strategy Type&nbsp*'); ?>
            <div class="control">
                <?php echo $form->renderDropDownList('StrategyMap[strategyType]', StrategyMap::getStrategyTypes()); ?>
            </div>
        </div>
    </div>

    <div class="all-33">
        <div class="control-group">
                <?php echo $form->renderLabel('Starting and Ending Periods&nbsp*'); ?>
            <div class="control">
                <div id="periodDate"></div>
                <?php echo $form->renderHiddenField('StrategyMap[startingPeriodDate]'); ?>
                <?php echo $form->renderHiddenField('StrategyMap[endingPeriodDate]'); ?>
                <?php echo $form->renderHiddenField('StrategyMap[name]'); ?>
            </div>
        </div>
    </div>

    <div class="all-33">
        <div class="control-group">
                <?php echo $form->renderLabel('&nbsp;'); ?>
            <div class="control">
                <?php
                echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat',
                    'style' => 'width: 100%; margin-left: 0px;'));
                ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $form->endComponent();
