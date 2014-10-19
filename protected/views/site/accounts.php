<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;

$accounts = $params['accounts'];
?>
<div class="all-45 push-center" style="margin-top: 50px;">
    <?php
    $form = new Form(array('action' => array('site/selectAccount'), 'class' => 'ink-form', 'hasFieldset' => true));

    echo $form->startComponent();
    echo $form->constructHeader('Account Selection');
    ?>
    <div class="ink-alert basic info">Your account is linked on multiple security roles. Please select the role you want to use in the system.</div>
    <div class="control-group">
        <?php echo $form->renderLabel("Security Role") ?>
        <div class="control append-button">
            <span><?php echo $form->renderDropDownList('userId', $accounts); ?></span>
            <?php echo $form->renderSubmitButton('Proceed', array('class' => 'ink-button blue flat')) ?>
        </div>
    </div>
    <?php echo $form->endComponent(); ?>
</div>