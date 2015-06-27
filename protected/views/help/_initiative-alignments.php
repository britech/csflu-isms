<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageAlignment" style="display: block; border-bottom: 1px solid black;">Manage Strategy Alignments</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#malign-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#malign-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#malign-5', 'Step 5') ?>
    </li>
    <li>
        <a name="malign-2"></a>
        Select the strategy map of the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="malign-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Initiative</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Initiative</strong> link, you will be redirected to the directory listing of Initiatives. 
        <br/>
        Click the <strong>View</strong> link beside the Initiative you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-1.png" style=";" alt="step-4"/>
    </li>
    <li>
        <a name="malign-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Manage Strategy Alignment</strong> link to perform management of Strategy Alignments data.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style=";" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Initiative's Strategy Alignments.
        <br/>
        To <strong>ADD</strong> strategy alignments, click <?php echo ApplicationUtils::generateLink('#addAlignment', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing strategy alignments, click <?php echo ApplicationUtils::generateLink('#deleteAlignment', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/initiative/manage-alignments.png" style=";" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addAlignment" style="display: block; border-bottom: 1px solid black;">Add Strategy Alignments</a>
<ol>
    <li>
        Select strategic objectives and/or measure profiles from the list and click the <strong>Add</strong> button to insert the new Strategy Alignments.
        <br/>
        <strong>Important Note:&nbsp;</strong>You can choose between strategic objectives and measure profiles to insert as Strategy Alignments for the selected Initiative.
        <br/>
        <strong>Tip:&nbsp;</strong>You can select <strong>TWO or MORE</strong> strategic objectives and/or measure profiles.
        <br/>
        <img src="protected/views/help/images/initiative/manage-alignments.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new strategy alignments is committed in the data source and you will be redirected to the management page of the Strategy Alignments.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageAlignment', 'Back to Manage Strategy Alignments'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteAlignment" style="display: block; border-bottom: 1px solid black;">Delete Strategy Alignment Entry</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Strategic Objective or Measure Profile entry you want to delete.
        <br/>
        <img src="protected/views/help/images/initiative/manage-alignments.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected Strategic Objective or Measure Profile.
        <br/>
        <img src="protected/views/help/images/initiative/delete-alignment.png" style=";" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of Strategic Objective or Measure Profile
    </li>
    <li>
        Upon successful validation, the objective or measure profile is removed in the data source and you will be redirected to the management page of the Strategy Alignments.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageAlignment', 'Back to Manage Strategy Alignments'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>