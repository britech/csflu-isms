<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\uam\LoginAccount;
?>
<div class="ink-alert block info" style="margin-top: 0px;">
    <h4>Account Information</h4>
    <p>
        <span style="display: block; font-weight: bold;">Owner</span>
        <span><?php echo $employee->getFullName(); ?></span>

        <span style="display: block; font-weight: bold; margin-top: 10px">Username</span>
        <span><?php echo $employee->loginAccount->username ?></span>

        <span style="display: block; margin-top: 20px; font-weight: bold; border-top: 1px solid black; text-align: right">Actions</span>
        <?php if ($employee->loginAccount->status == LoginAccount::STATUS_ACTIVE): ?>
            <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/linkForm', 'id' => $employee->id), 'Link a Security Role') ?></span>
            <span style="display: block;"><?php echo ApplicationUtils::generateLink('#', 'Reset Password', array('id' => 'reset')) ?></span>
            <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/confirmStatusToggle', 'id' => $employee->id, 'stat' => 0), 'Disable Account') ?></span>
        <?php elseif ($employee->loginAccount->status == LoginAccount::STATUS_DISABLED): ?>
            <span style="display: block;"><?php echo ApplicationUtils::generateLink(array('user/confirmStatusToggle', 'id' => $employee->id, 'stat' => 1), 'Activate Account') ?></span>
        <?php endif; ?>
    </p>
</div>