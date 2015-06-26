<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageDepartment" style="display: block; border-bottom: 1px solid black;">Manage Departments</a>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('department/index'), 'Departments'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        You will be then redirected to the management page of Department entries.
        <br/>
        To <strong>ADD</strong>, click <?php echo ApplicationUtils::generateLink('#addDepartment', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong>, click <?php echo ApplicationUtils::generateLink('#updateDepartment', 'here'); ?>.
        <img src="protected/views/help/images/admin/manage-departments.png" alt="step-1"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addDepartment" style="display: block; border-bottom: 1px solid black;">Department Enlistment</a>
<ol>
    <li>
        Click the <strong>Add New Department</strong> link to begin enlistment of a department entry.
        <img src="protected/views/help/images/admin/manage-departments.png" alt="step-1"/>
    </li>
    <li>
        The application will load the department entry enlistment form.
        <br/>
        Complete the input form and click the <strong>Enlist</strong> button to insert the new department entry.
        <img src="protected/views/help/images/admin/add-department.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new department entry is saved in the data source and you will be redirected to the management page of the departments.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageDepartment', 'Back to Manage Departments'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateDepartment" style="display: block; border-bottom: 1px solid black;">Update Department Entry</a>
<ol>
    <li>
        Click the <strong>Update Department</strong> link beside the department entry you want to update.
        <img src="protected/views/help/images/admin/manage-departments.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will load the department entry update form.
        <br/>
        Update the field value and click the <strong>Update</strong> button to apply the changes.
        <img src="protected/views/help/images/admin/update-department.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the updated department entry is saved in the data source and you will be redirected to the management page of the departments.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageDepartment', 'Back to Manage Departments'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>