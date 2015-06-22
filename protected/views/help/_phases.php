<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="managePhase" style="display: block; border-bottom: 1px solid black;">Manage Phases</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mphase-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mphase-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#mphase-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mphase-2"></a>
        Select the strategy map of the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="mphase-3"></a>
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
        <a name="mphase-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Manage Phases</strong> link to perform management of Phase data.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Measure Profile's Phases.
        <br/>
        To <strong>ADD</strong> a new Phase entry, click <?php echo ApplicationUtils::generateLink('#addPhase', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing Phase entry, click <?php echo ApplicationUtils::generateLink('#updatePhase', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Phase entry, click <?php echo ApplicationUtils::generateLink('#deletePhase', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/measure-profile/manage-target.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addPhase" style="display: block; border-bottom: 1px solid black;">Add Phase Entry</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Enlist</strong> button to insert the new Phase data.
        <br/>
        Please bear in mind that the <strong>Phase Number</strong> cannot be re-used once it's <strong>enlisted</strong>.
        <br/>
        <img src="protected/views/help/images/initiative/manage-phases.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new phase data is committed in the data source and you will be redirected to the management page of the Phases.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePhase', 'Back to Manage Phases'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updatePhase" style="display: block; border-bottom: 1px solid black;">Update Phase Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Phase entry you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/manage-phases.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the update form of the selected Phase entry.
        <br/>
        Change the field values that you want to update and click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/initiative/update-phase.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the update phase data is committed in the data source and you will be redirected to the management page of the Phases.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePhase', 'Back to Manage Phases'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deletePhase" style="display: block; border-bottom: 1px solid black;">Delete Phase Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Phase entry you want to delete.
        <br/>
        <img src="protected/views/help/images/initiative/manage-phases.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected Phase entry.
        <br/>
        <img src="protected/views/help/images/initiative/delete-phase.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of Phase entry
    </li>
    <li>
        Upon successful validation, the phase data is removed in the data source and you will be redirected to the management page of the Phases.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePhase', 'Back to Manage Phases'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>