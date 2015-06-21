<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageTarget" style="display: block; border-bottom: 1px solid black;">Manage Targets</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mtarget-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mtarget-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Measure Profile, proceed to <?php echo ApplicationUtils::generateLink('#mtarget-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mtarget-2"></a>
        Select the strategy map of the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="mtarget-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profile</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>View</strong> link beside the Measure Profile you want to update.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-1.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        <a name="mtarget-5"></a>
        Afterwards, you are redirected to the View page of the selected Measure Profile.
        <br/>
        Click the <strong>Manage Targets</strong> link to perform management of Target data.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Measure Profile's Targets.
        <br/>
        To <strong>ADD</strong> a new Target data, click <?php echo ApplicationUtils::generateLink('#addTarget', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing Target data, click <?php echo ApplicationUtils::generateLink('#updateTarget', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Target data, click <?php echo ApplicationUtils::generateLink('#deleteTarget', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-target.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addTarget" style="display: block; border-bottom: 1px solid black;">Add Target Data</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Add</strong> button to insert the new target data.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-target.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new target data is committed in the data source and you will be redirected to the management page of the Targets.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTarget', 'Back to Manage Targets'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateTarget" style="display: block; border-bottom: 1px solid black;">Update Target Data</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Target data that you want to update.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-target.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the update form of the selected Target data.
        <br/>
        Update the field values needed to be changed. Please bear in mind that the <strong>Year Covered</strong> field is not updateable.
        <br/>
        To apply the changes, click the <strong>Update</strong> button.
        <br/>
        <img src="protected/views/help/images/measure-profile/update-target.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated target data is committed in the data source and you will be redirected to the management page of the Targets.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTarget', 'Back to Manage Targets'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteTarget" style="display: block; border-bottom: 1px solid black;">Delete Target Data</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Target data that you want to delete.
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-target.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected target data.
        <br/>
        <img src="protected/views/help/images/measure-profile/delete-target.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of the selected target data.
    </li>
    <li>
       After the selected target data is deleted in the data source, you will be redirected to the management page of the Targets.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTarget', 'Back to Manage Targets'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>