<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageLeadMeasure" style="display: block; border-bottom: 1px solid black;">Manage Lead Measures</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mlead-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mlead-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Unit Breakthrough, proceed to <?php echo ApplicationUtils::generateLink('#mlead-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mlead-2"></a>
        Select the strategy map of the Unit Breakthrough from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" alt="step-2"/>
    </li>
    <li>
        <a name="mlead-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Unit Breakthroughs</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Unit Breakthroughs</strong> link, you will be redirected to the directory listing of Unit Breakthroughs. 
        <br/>
        Click the <strong>View</strong> link beside the Unit Breakthrough you want to update.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-1.png" alt="step-4"/>
    </li>
    <li>
        <a name="mlead-5"></a>
        Afterwards, you are redirected to the View page of the selected Unit Breakthrough.
        <br/>
        Click the <strong>Manage Lead Measures</strong> link to perform management of Lead Measure entries.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-3.png" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Unit Breakthrough's Lead Measures.
        <br/>
        To <strong>ADD</strong>, click <?php echo ApplicationUtils::generateLink('#addLeadMeasure', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong>, click <?php echo ApplicationUtils::generateLink('#updateLeadMeasure', 'here'); ?>
        <br/>
        To <strong>ENABLE</strong>, click <?php echo ApplicationUtils::generateLink('#enableLeadMeasure', 'here'); ?>
        <br/>
        To <strong>DISABLE</strong>, click <?php echo ApplicationUtils::generateLink('#disableLeadMeasure', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/ubt/manage-lead-measures.png" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addLeadMeasure" style="display: block; border-bottom: 1px solid black;">Add Lead Measure Entry</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Enlist</strong> button to insert the new Lead Measure data.
        <br/>
        <strong>Important Note:&nbsp;</strong>You can only enlist a <strong>maximum of 2 active Lead Measures</strong>.
        <br/>
        <img src="protected/views/help/images/ubt/manage-lead-measures.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new phase data is committed in the data source and you will be redirected to the management page of the Lead Measures.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLeadMeasure', 'Back to Manage Lead Measures'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateLeadMeasure" style="display: block; border-bottom: 1px solid black;">Update Lead Measure Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Lead Measure entry you want to update.
        <br/>
        <img src="protected/views/help/images/ubt/manage-lead-measures.png" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the update form of the selected Lead Measure entry.
        <br/>
        Change the field values that you want to update and click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/ubt/update-lead-measure.png" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated phase data is committed in the data source and you will be redirected to the management page of the Lead Measures.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLeadMeasure', 'Back to Manage Lead Measures'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="enableLeadMeasure" style="display: block; border-bottom: 1px solid black;">Enable a Lead Measure</a>
<ol>
    <li>
        Click the <strong>Enable</strong> link beside the Lead Measure entry you want to activate.
        <br/>
        <img src="protected/views/help/images/ubt/manage-lead-measures.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to activate the selected Lead Measure entry.
        <br/>
        <img src="protected/views/help/images/ubt/enable-lead-measure.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm activation of Lead Measure entry
    </li>
    <li>
        Upon successful validation, the Lead Measure entry will be updated in the data source and you will be redirected to the management page of the Lead Measures.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLeadMeasure', 'Back to Manage Lead Measure'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="disableLeadMeasure" style="display: block; border-bottom: 1px solid black;">Disable a Lead Measure</a>
<ol>
    <li>
        Click the <strong>Disable</strong> link beside the Lead Measure entry you want to disable.
        <br/>
        <img src="protected/views/help/images/ubt/manage-lead-measures.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to disable the selected Lead Measure entry.
        <br/>
        <img src="protected/views/help/images/ubt/disable-lead-measure.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deactivation of Lead Measure entry
    </li>
    <li>
        Upon successful validation, the Lead Measure entry will be updated in the data source and you will be redirected to the management page of the Lead Measures.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageLeadMeasure', 'Back to Manage Lead Measure'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>