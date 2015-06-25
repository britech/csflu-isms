<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageLink" style="display: block; border-bottom: 1px solid black;">Manage Account Linking</a>
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
        To <strong>LINK</strong> a security role, click <?php echo ApplicationUtils::generateLink('#linkAccount', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong> linked security role, click <?php echo ApplicationUtils::generateLink('#updateLink', 'here'); ?>.
        <br/>
        To <strong>UNLINK</strong> a security role, click <?php echo ApplicationUtils::generateLink('#deleteLink', 'here'); ?>.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-2"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="linkAccount" style="display: block; border-bottom: 1px solid black;">Link a Security Role</a>
<ol>
    <li>
        Click the <strong>Link a Security Role</strong> link to being security role account linking.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the security role linking form.
        <br/>
        Complete the input form and click the <strong>Link to Account</strong> button to link the security role to the selected account.
        <img src="protected/views/help/images/admin/link-role.png" alt="step-1"/>
    </li>
    <li>
        The newly linked security role will be saved in the data source and you will be redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLink', 'Back to Manage Account Linking'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateLink" style="display: block; border-bottom: 1px solid black;">Update Linked Security Role</a>
<ol>
    <li>
        Click the <strong>Update Link</strong> link beside the security role linked in the user account.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the security role linking update form.
        <br/>
        <strong>Important Note:&nbsp;</strong>You can <strong>ONLY</strong> update the <strong>Security Role</strong> and <strong>Position</strong> fields.
        <br/>
        Complete the input form and click the <strong>Update Link</strong> button to apply the changes.
        <img src="protected/views/help/images/admin/update-link-role.png" alt="step-1"/>
    </li>
    <li>
        The updated linked security role will be saved in the data source and you will be redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLink', 'Back to Manage Account Linking'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteLink" style="display: block; border-bottom: 1px solid black;">Unlink Security Role</a>
<ol>
    <li>
        Click the <strong>Unlink Role</strong> link beside the security role linked in the user account.
        <img src="protected/views/help/images/admin/user-registration-2.png" alt="step-1"/>
    </li>
    <li>
        The application will prompt for a confirmation to unlink the selected security role of the user account.
        <img src="protected/views/help/images/admin/delete-link-role.png" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm unlinking of selected security role.
    </li>
    <li>
        The linked security role will be removed in the data source and you will be redirected to the account management page of the selected employee.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLink', 'Back to Manage Account Linking'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>