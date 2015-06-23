<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageActivity" style="display: block; border-bottom: 1px solid black;">Manage Activities</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mact-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mact-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#mact-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mact-2"></a>
        Select the strategy map of the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="mact-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Initiative</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Initiative</strong> link, you will be redirected to the directory listing of Initiatives. 
        <br/>
        Click the <strong>View</strong> link beside the Initiative you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-1.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        <a name="mact-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Manage Activities</strong> link to perform management of Activity data.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Initiative's Activities.
        <br/>
        To <strong>ADD</strong> a new Activity entry, click <?php echo ApplicationUtils::generateLink('#addActivity', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing Activity entry, click <?php echo ApplicationUtils::generateLink('#updateActivity', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Activity entry, click <?php echo ApplicationUtils::generateLink('#deleteActivity', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/initiative/manage-activities.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addActivity" style="display: block; border-bottom: 1px solid black;">Add Activity Entry</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Enlist</strong> button to insert the new Activity data.
        <br/>

        Please take note of the following:
        <br/>
        &ast;&nbsp;When inputting an amount in the <strong>Budget</strong> field, input also the <strong>Source of Budget</strong> field.
        <br/>
        &ast;&nbsp;<strong>Activity Number</strong> can be repeated as long it is aligned in the <strong>Phase Number</strong> of the <strong>Component</strong> selected.
        <br/>
        <img src="protected/views/help/images/initiative/manage-activities.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new activity data is committed in the data source and you will be redirected to the management page of the Activities.
        <br/>
        <strong>Important Note:&nbsp;</strong>Activities that are enlisted are set to <strong>PENDING</strong> status <strong>ONLY</strong>. To update the status of the activity entry, click <?php echo ApplicationUtils::generateLink('#activityDashboard', 'here'); ?>.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageActivity', 'Back to Manage Activities'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateActivity" style="display: block; border-bottom: 1px solid black;">Update Activity Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Activity entry you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/manage-activities.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the update form of the selected Activity entry.
        <br/>
        Change the field values that you want to update and click the <strong>Update</strong> button to apply the changes.
        <br/>
        <strong>Note:&nbsp;</strong> Same rules for adding activity entry is applied.
        <br/>
        <img src="protected/views/help/images/initiative/update-activity.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the updated activity data is committed in the data source and you will be redirected to the management page of the Components.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageActivity', 'Back to Manage Activities'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteActivity" style="display: block; border-bottom: 1px solid black;">Delete Activity Entry</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Activity entry you want to delete.
        <br/>
        <img src="protected/views/help/images/initiative/manage-activities.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected Activity entry.
        <br/>
        <img src="protected/views/help/images/initiative/delete-activity.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of the Activity entry
    </li>
    <li>
        Upon successful validation, the activity data is removed in the data source and you will be redirected to the management page of the Activities.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageActivity', 'Back to Manage Activities'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>