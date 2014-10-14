<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
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
        <?php if (isset($params['login.notif'])): ?>
            <div class="ink-alert basic info"><?php echo $params['login.notif']; ?></div>
        <?php endif; ?>

        <div class="control-group column-group quarter-gutters">
            <?php echo Form::renderLabel('Username', array('class' => 'all-20')); ?>
            <div class="control all-80">
                <?php echo Form::renderTextField('Login[username]'); ?>
            </div>
        </div>
        <div class="control-group column-group quarter-gutters">
            <?php echo Form::renderLabel('Password', array('class' => 'all-20')); ?>
            <div class="control all-80">
                <?php echo Form::renderPasswordField('Login[password]'); ?>
                <?php echo Form::renderSubmitButton('Authenticate', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left:0px;')); ?>
            </div>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
</div>
