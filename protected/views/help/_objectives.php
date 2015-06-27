<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageObjective" style="display: block; border-bottom: 1px solid black;">Manage Objectives</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mobj-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mobj-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="mobj-2"></a>
        Select the strategy map to update from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="mobj-3"></a>
        Upon selection of the strategy map, you will be redirected to its View page. Click <strong>Manage Objectives</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        You will be then redirected to the management page of the Objectives.
        <br/>
        To <strong>ADD</strong> a new objective, click <?php echo ApplicationUtils::generateLink('#addObjective', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong> an existing objective, click <?php echo ApplicationUtils::generateLink('#updateObjective', 'here'); ?>.
        <br/>
        To <strong>DELETE</strong> an existing objective, click <?php echo ApplicationUtils::generateLink('#deleteObjective', 'here'); ?>.
        <br/>
        <img src="protected/views/help/images/map/manage-objectives.png" style=";" alt="step-4"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addObjective" style="display: block; border-bottom: 1px solid black;">Add Objective</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Create</strong> button to insert the new objective.
        <br/>
        <img src="protected/views/help/images/map/manage-objectives.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new objective is committed in the data source and you will redirected to the management page of the Objectives.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageObjective', 'Back to Manage Objectives'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateObjective" style="display: block; border-bottom: 1px solid black;">Update Objective Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Objective that you want to update.
        <br/>
        <img src="protected/views/help/images/map/manage-objectives.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Update Form of the objective.
        <br/>
        After inputting the updated data, click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/map/update-objective.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated theme will be committed in the data source and you will be redirected to management page of the Objectives.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageObjective', 'Back to Manage Objectives'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteObjective" style="display: block; border-bottom: 1px solid black;">Delete Objective Entry</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Objective that you want to delete.
        <br/>
        <img src="protected/views/help/images/map/manage-objectives.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected objective.
        <br/>
        <img src="protected/views/help/images/map/delete-objective.png" style=";" alt="step-2"/>
    </li>
    <li>
        Click <strong>Yes</strong> button to confirm deletion the selected objective.
    </li>
    <li>
        Upon confirmation, you will be redirected to the management page of the objective to reflect the updated list.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageObjective', 'Back to Manage Objectives'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>