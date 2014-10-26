<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$form = new Form(array(
    'action'=>array('department/update'),
    'class'=>'ink-form',
    'hasFieldset'=>true
));

echo $form->startComponent();
echo $form->constructHeader('Update Department');

if(isset($params['data']) && !empty($params['data'])){
    $department = $params['data'];
}
?>

<?php if(isset($params['validation']) && !empty($params['validation'])):?>
<div style="margin-top: 10px;">
    <?php $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));?>
</div>
<?php endif;?>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Code', array('class'=>'all-20 align-right'))?>
    <div class="control all-80">
        <?php echo $form->renderTextField('Department[code]', array(
            'style'=>'width: 150px;', 
            'value'=>$department->code));?>
    </div>
</div>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Description', array('class'=>'all-20 align-right'))?>
    <div class="control all-80">
        <?php echo $form->renderTextField('Department[name]', array(
            'value'=>$department->name));?>
        <?php echo $form->renderHiddenField('Department[id]', array(
            'value'=>$department->id
        ));?>
        <?php echo $form->renderSubmitButton('Update', array(
            'class'=>'ink-button green flat',
            'style'=>'margin-top: 1em; margin-left: 0px;'));?>
    </div>
</div>
<?php echo $form->endComponent();

