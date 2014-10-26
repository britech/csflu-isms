<?php 
namespace org\csflu\isms\views;
use org\csflu\isms\util\FormGenerator as Form;

if(isset($params['data']) && !empty($params['data'])){
    $data = $params['data'];
}

$form = new Form(array(
    'action'=>array(isset($data) ? 'position/update' : 'position/create'),
    'class'=>'ink-form',
    'hasFieldset'=>true
));
echo $form->startComponent();

echo $form->constructHeader(isset($data) ? 'Update Position' : 'Add Position');
?>

<?php if (isset($params['notif']) && !empty($params['notif'])): ?>
<div style="margin-top:10px;">
    <?php $this->renderPartial('commons/_notification', array('notif'=>$params['notif']));?>
</div>
<?php endif;?>

<?php if(isset($params['validation']) && !empty($params['validation'])):?>
<div style="margin-top: 10px;">
    <?php $this->viewWarningPage('Validation error. Please check your entries', implode('<br/>', $params['validation']));?>
</div>
<?php endif;?>

<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Description', array('class'=>'all-20 align-right'));?>
    <div class="control all-80 append-button">
        <span><?php echo $form->renderTextField('Position[name]', array('value'=>  isset($data) ? $data->name : ''));?></span>
        <?php echo $form->renderSubmitButton('Enlist', array('class'=>'ink-button green flat'));?>
    </div>
    <?php
    if(isset($data)){
        echo $form->renderHiddenField('Position[id]', array('value'=>$data->id));
    }
    ?>
</div>
<?php echo $form->endComponent();?>
<script type="text/javascript" src="protected/js/position/index.js"></script>
<div id="positionList" style="margin-bottom: 1em;"></div>
