<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageRole" style="display: block; border-bottom: 1px solid black;">Manage Security Roles</a>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('user/index'), 'User Management'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the User Management module page.
        <br/>
        Click the <strong>Security Role</strong> link to perform security role management.
        <img src="protected/views/help/images/admin/manage-users.png" alt="step-1"/>
    </li>
    <li>
        The application will then create a list of security roles retrieved from the data source.
        <br/>
        To <strong>CREATE</strong>, click <?php echo ApplicationUtils::generateLink('#createRole', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong>, click <?php echo ApplicationUtils::generateLink('#updateRole', 'here'); ?>.
        <br/>
        To <strong>DELETE</strong>, click <?php echo ApplicationUtils::generateLink('#deleteRole', 'here'); ?>.
        <img src="protected/views/help/images/admin/manage-roles.png" alt="step-2"/>
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="createRole" style="display: block; border-bottom: 1px solid black;">Create a Security Role</a>
<ol>
    <li>
        Click the <strong>Add New Security Role</strong> button to begin enlistment of security role.
        <img src="protected/views/help/images/admin/manage-roles.png" alt="step-2"/>
    </li>
    <li>
        You will be then redirected to the enlistment form for security roles.
        <br/>
        Select the allowable actions for the security to enlist and click <strong>Add Security Role</strong> button to commit the enlistment.
        <img src="protected/views/help/images/admin/add-role.png" alt="step-2"/>
    </li>
    <li>
        The new security role will be enlisted in the data source and you will be redirected to the management page of security roles.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageRole', 'Back to Manage Security Roles'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateRole" style="display: block; border-bottom: 1px solid black;">Update Security Role</a>
<ol>
    <li>
        Click the <strong>Update Details</strong> link beside the security role you want to update.
        <img src="protected/views/help/images/admin/manage-roles.png" alt="step-2"/>
    </li>
    <li>
        You will be then redirected to the update form of the selected security role.
        <br/>
        Select the allowable actions for the security to enlist and click <strong>Update Security Role</strong> button to apply the changes.
        <img src="protected/views/help/images/admin/update-role.png" alt="step-2"/>
    </li>
    <li>
        The updated security role will be saved in the data source and you will be redirected to the overview page of the selected security role.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageRole', 'Back to Manage Security Roles'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteRole" style="display: block; border-bottom: 1px solid black;">Delete Security Role</a>
<div class="ink-alert basic">
    <strong>Warning:&nbsp;</strong>Confirming deletion of a selected <strong>Security Role</strong> will also trigger removal of accounts linked to the <strong>Security Role</strong>.
</div>
<ol>
    <li>
        Click the <strong>Remove</strong> link beside the security role you want to delete.
        <img src="protected/views/help/images/admin/manage-roles.png" alt="step-1"/>
    </li>
    <li>
        The application will then ask for confirmation to delete the selected security role.
        <img src="protected/views/help/images/admin/delete-role.png" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of the selected security role.
    </li>
    <li>
        The selected security role will be deleted in the data source together with the linked accounts and you will be redirected to management page of the security roles.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageRole', 'Back to Manage Security Roles'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>