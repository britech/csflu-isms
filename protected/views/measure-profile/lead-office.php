<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
?>
<script type="text/javascript" src="protected/js/measure-profile/lead-office.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php
        $form = new Form(array(
            'action' => array($model->isNew() ? 'measure/insertLeadOffice' : 'measure/updateLeadOffice'),
            'class' => 'ink-form',
            'hasFieldset' => true
        ));
        echo $form->startComponent();
        echo $form->constructHeader($model->isNew() ? "Add Lead Office" : "Update Lead Office");
        ?>
        <div class="ink-alert basic info">
            <strong>Important Note:&nbsp;</strong>Fields with * are required.
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
        <?php if($model->validationMode !== \org\csflu\isms\core\Model::VALIDATION_MODE_UPDATE):?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'department', array('required' => true)); ?>
            <div class="control">
                <div id="department-input"></div>
            </div>
        </div>
        <?php endif;?>
        <div class="control-group">
            <?php echo $form->renderLabel($model, 'designation', array('required' => true)); ?>
            <ul class="control unstyled" style="margin-top: 0px; margin-bottom: 0px;">
                <?php
                foreach ($designationTypes as $designationValue => $designationType) {
                    echo "<li>{$form->renderCheckBox($model, 'designation', $designationType, array('value' => $designationValue))}</li>";
                }
                ?>
            </ul>
            <?php
            echo $form->renderHiddenField($departmentModel, 'id', array('id' => 'department'));
            echo $form->renderHiddenField($measureProfileModel, 'id', array('id' => 'profile'));
            echo $form->renderHiddenField($model, 'validationMode');
            echo $form->renderHiddenField($model, 'id');
            if ($model->isNew()) {
                echo $form->renderSubmitButton('Create', array('class' => 'ink-button green flat', 'style' => 'margin-top:1em; margin-left:0px;'));
            } else {
                echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top:1em; margin-left:0px;'));
            }
            ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <div id="lead-offices"></div>
    </div>
</div>
