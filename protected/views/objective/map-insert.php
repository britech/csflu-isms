<?php

namespace org\csflu\isms\views;

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
<div id="validation-container"></div>
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
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'description', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <?php echo $form->renderTextField($model, 'description'); ?>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'perspective', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList($perspectiveModel, 'id', $perspectives); ?>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'theme', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <?php echo $form->renderDropDownList($themeModel, 'id', $themes); ?>
    </div>
</div>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'period', array('class' => 'all-20 align-right', 'required' => true)); ?>
    <div class="control all-80">
        <div id="periods"></div>
    </div>
</div>
<?php echo $form->endComponent(); ?>
<div id="objectives"></div>





