<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageStatus" style="display: block; border-bottom: 1px solid black;">Manage Account Status</a>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('user/index'), 'User Management'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the User Management module page.
        <br/>
        Click the <strong>Manage Account</strong> link to the employee you want to manage.
        <img src="protected/views/help/images/admin/manage-users.png" alt="step-1"/>
    </li>
    <li>
        The application will load the account management page of the selected employee.
        <br/>
        To <strong>ACTIVATE</strong>, click <?php echo ApplicationUtils::generateLink('#activateAccount', 'here'); ?>.
        <br/>
        To <strong>DEACTIVATE</strong>, click <?php echo ApplicationUtils::generateLink('#disableAccount', 'here'); ?>.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-2"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="activateAccount" style="display: block; border-bottom: 1px solid black;">Activate Account</a>
<ol>
    <li>
        Click the <strong>Activate Account</strong> link to activate the user account.
        <img src="protected/views/help/images/admin/manage-account-disabled.png" alt="step-1"/>
    </li>
    <li>
        The application will prompt for a confirmation to activate the user account.
        <img src="protected/views/help/images/admin/activate-account.png" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm activation of selected user account.
    </li>
    <li>
        The user account status will be updated in the data source and you will be redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageStatus', 'Back to Manage Account Status'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="disableAccount" style="display: block; border-bottom: 1px solid black;">Disable Account</a>
<ol>
    <li>
        Click the <strong>Disable Account</strong> link to disable the user account.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-1"/>
    </li>
    <li>
        The application will prompt for a confirmation to disable the user account.
        <img src="protected/views/help/images/admin/disable-account.png" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deactivation of selected user account.
    </li>
    <li>
        The user account status will be updated in the data source and you will be redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageStatus', 'Back to Manage Account Status'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>