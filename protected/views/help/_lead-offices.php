<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageOffice" style="display: block; border-bottom: 1px solid black;">Manage Lead Offices</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mleado-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mlead-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Measure Profile, proceed to <?php echo ApplicationUtils::generateLink('#mlead-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mlead-2"></a>
        Select the strategy map of the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="mlead-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profile</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>View</strong> link beside the Measure Profile you want to update.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-1.png" style=";" alt="step-4"/>
    </li>
    <li>
        <a name="mlead-5"></a>
        Afterwards, you are redirected to the View page of the selected Measure Profile.
        <br/>
        Click the <strong>Manage Lead Offices</strong> link to perform management of Lead Offices.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" style=";" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Measure Profile's Lead Offices.
        <br/>
        To <strong>ADD</strong> a new Lead Office, click <?php echo ApplicationUtils::generateLink('#addOffice', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing Lead Office, click <?php echo ApplicationUtils::generateLink('#updateOffice', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Lead Office, click <?php echo ApplicationUtils::generateLink('#deleteOffice', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-lead-office.png" style=";" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addOffice" style="display: block; border-bottom: 1px solid black;">Add Lead Office</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Create</strong> button to insert the new lead office.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-lead-office.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new lead office is committed in the data source and you will be redirected to the management page of the Lead Offices.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageOffice', 'Back to Manage Lead Offices'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateOffice" style="display: block; border-bottom: 1px solid black;">Update Lead Office Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Lead Office you want to update.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-lead-office.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the update form of the selected Lead Office.
        <br/>
        Update the field values to be changed and click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/measure-profile/update-lead-office.png" style=";" alt="step-3"/>
    </li>
    <li>
        Upon successful validation, the updated lead office is committed in the data source and you will be redirected to the management page of the Lead Offices.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageOffice', 'Back to Manage Lead Offices'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteOffice" style="display: block; border-bottom: 1px solid black;">Delete Lead Office Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Lead Office you want to delete.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-lead-office.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for a confirmation to delete the selected Lead Office.
        <br/>
        <img src="protected/views/help/images/measure-profile/delete-lead-office.png" style=";" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm the deletion of the selected Lead Office.
    </li>
    <li>
        After the Lead Office is <strong>deleted</strong>, you will be redirected to the management page of the Lead Offices.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageOffice', 'Back to Manage Lead Offices'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>