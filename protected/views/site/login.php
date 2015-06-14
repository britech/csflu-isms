<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
use org\csflu\isms\core\ApplicationConstants as ApplicationConstants;
?>
<div class="ink-grid" style="margin-top: 150px;">
    <div class="all-45 push-center">
        <?php
        $form = new Form(array('action' => array('site/authenticate'),
            'class' => 'ink-form',
            'hasFieldset' => true));
        echo $form->startComponent();
        echo $form->constructHeader(ApplicationConstants::APP_NAME);
        ?>
        <div style="margin-top: 20px; margin-bottom: 0px;">
            <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
        </div>
        <div class="control-group column-group quarter-gutters">
            <?php echo $form->renderLabel($model, 'username', array('class' => 'all-20')); ?>
            <div class="control all-80">
                <?php echo $form->renderTextField($model, 'username'); ?>
            </div>
        </div>
        <div class="control-group column-group quarter-gutters">
            <?php echo $form->renderLabel($model, 'password', array('class' => 'all-20')); ?>
            <div class="control all-80">
                <?php echo $form->renderPasswordField($model, 'password'); ?>
                <?php echo $form->renderSubmitButton('Authenticate', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left:0px;')); ?>
            </div>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
