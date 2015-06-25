<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="resetAccount" style="display: block; border-bottom: 1px solid black;">Account Password Reset</a>
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
        Click the <strong>Reset Password</strong> link to perform account password reset.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to reset the account password through the account's username.
        <img src="protected/views/help/images/admin/password-reset.png" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm password reset.
    </li>
    <li>
        The account password will be reverted back to the account's username and updated to the data source. You will be then redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

