<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="enlist" style="display: block; border-bottom: 1px solid black;">Account Enlistment</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>This procedure connects to the database instance of the HRMIS, please update the configuration to ensure successful validation of inputted employee number.
</div>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('user/index'), 'User Management'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the User Management module page.
        <br/>
        Click the <strong>Create Account</strong> button to start enlisting a new account.
        <img src="protected/views/help/images/admin/manage-users.png" alt="create-step-1"/>
    </li>
    <li>
        The application will load the enlistment form for the new user account.
        <br/>
        Accomplish the employee number first, once the input is validated, select the position and security role associated in the new account.
        <br/>
        Click the <strong>Register</strong> button once you have completed the form.
        <img src="protected/views/help/images/admin/user-registration-1.png" alt="create-step-2"/>
    </li>
    <li>
        The new account will be saved in the data source and you will be redirected to the management page of the newly-inserted account.
        <br/>
        <strong>Important Note:&nbsp;</strong>Upon allocation of new account, the password is set to the account username.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="create-step-4"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?> 
<?php $this->renderPartial('help/_manage-account'); ?>
<?php $this->renderPartial('help/_manage-user-status'); ?>
<?php $this->renderPartial('help/_account-reset'); ?>
<?php $this->renderPartial('help/_security-roles'); ?>
<?php $this->renderPartial('help/_departments'); ?>
<?php $this->renderPartial('help/_positions'); ?>