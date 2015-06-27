<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="login" style="display: block; border-bottom: 1px solid black;">Logging-in</a>
<ol>
    <li>
        Go to the application URL of CSFLU Integrated Strategy Management System and the application will load the Login page.
        <img src="protected/views/help/images/commons/login-form.png" alt="step-1"/>
    </li>
    <li>Enter your assigned username and password.</li>
    <li>
        Upon successful account verification, you will be redirected to the Home page of the application.<br/>
        <img src="protected/views/help/images/commons/index.png" alt="step-3"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="multipleAccounts" style="display: block; border-bottom: 1px solid black;">Logging-in with Multiple Accounts</a>
<ol>
    <li>
        Go to the application URL of CSFLU Integrated Strategy Management System and the application will load the Login page.
        <img src="protected/views/help/images/commons/login-form.png" alt="step-1"/>
    </li>
    <li>Enter your assigned username and password.</li>
    <li>
        Upon successful account verification, you will be redirected to the Accounts Selection page.
        <img src="protected/views/help/images/commons/multiple-accounts.png" alt="step-3"/>
    </li>
    <li>Select the linked account you want to use in the application.</li>
    <li>
        After selecting the specified account, you will be redirected to the Home page of the application.
        <img src="protected/views/help/images/commons/index.png" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="changePassword" style="display: block; border-bottom: 1px solid black;">Changing Account Password</a>
<ol>
    <li>
        From the application's Home page, click <strong>My Profile&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink('user/viewChangePasswordForm', 'Change Password') ?></strong>&nbsp;link
        <img src="protected/views/help/images/commons/index.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Change Password form.
        <img src="protected/views/help/images/commons/update-password-form.png" alt="step-2"/>
    </li>
    <li>Enter your current password to enable password update.</li>
    <li>
        Upon successful verification of the current password, you will need to supply the <strong>NEW</strong> password.
        <img src="protected/views/help/images/commons/update-password-suc-old.png" alt="step-4"/>
    </li>
    <li>
        Enter the <strong>NEW</strong> password in the <strong>New Password</strong> field <br/>
        and <strong>re-type</strong> the <strong>NEW</strong> password in the <strong>Confirm Password</strong> field for validation.
    </li>
    <li>
        Upon successful validation of the <strong>NEW</strong> password, click the <strong>Update</strong> button to apply the update.
    </li>
    <li>DONE.</li>
</ol>
<?php
echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block;'));
