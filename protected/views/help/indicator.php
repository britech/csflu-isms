<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="enlist" style="display: block; border-bottom: 1px solid black;">Indicator Enlistment</a>
<ol>
    <li>
        From the application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('km/index'), 'Knowledge Management'); ?></strong>.
        <br/>
        <img src="protected/views/help/images/commons/index.png" style=";" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Knowledge Management module page.
        <br/>
        Click the <?php echo ApplicationUtils::generateLink(array('km/indicators'), 'Indicators') ?> link
        <br/>
        <img src="protected/views/help/images/indicator/create-step-1.png" style=";" alt="create-step-1"/>
    </li>
    <li>
        Click the <?php echo ApplicationUtils::generateLink(array('indicator/enlist'), 'Enlist an Indicator') ?> link.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-2.png" style=";" alt="create-step-2"/>
    </li>
    <li>
        Upon clicking the link, it will display an entry data form for the <strong>NEW</strong> indicator to be enlisted.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-3.png" style=";" alt="create-step-3"/>
    </li>
    <li>
        Upon completion of the form, the inputs are validated and saved in the data source;<br/>
        afterwards, you will be redirected to the View page of the newly enlisted indicator.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-4.png" style=";" alt="create-step-4"/>
    </li>
    <li>
        To update the Indicator data, click <?php echo ApplicationUtils::generateLink('#step-3', 'here'); ?>
        <br/>
        To manage the Baselines data, click <?php echo ApplicationUtils::generateLink('#b-step-3', 'here'); ?>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="update" style="display: block; border-bottom: 1px solid black;">Update Indicator Data</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Knowledge Management</strong>&nbsp;module&nbsp;<?php echo ApplicationUtils::generateLink('#enlist', 'here'); ?> and proceed to&nbsp;<?php echo ApplicationUtils::generateLink('#step-2', 'Step 2') ?>.
        <br/>
        If you are in the View page of the Indicator, proceed to&nbsp;<?php echo ApplicationUtils::generateLink('#step-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="step-2"></a>
        Select an indicator to update and click the <strong>Manage</strong> link beside the selected indicator.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-2.png" style=";" alt="update-step-2"/>
    </li>
    <li>
        <a name="step-3"></a>
        Upon clicking the <strong>Manage</strong> link, you will be redirected to the View page of the selected indicator.
        <br/>
        Click the <strong>Update Indicator</strong> link to update the Indicator data.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-4.png" style=";" alt="update-step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Update Indicator</strong> link, you will be redirected to the Update Form page of the selected indicator.
        <br/>
        <img src="protected/views/help/images/indicator/update-indicator.png" style=";" alt="update-step-4"/> 
    </li>
    <li>Input the necessary data to update the Indicator information.</li>
    <li>Click the <strong>Update</strong> button to apply the changes</li>
    <li>
        Upon successful validation, the updated data will be committed on the data source 
        <br/>
        and you will be redirected to the View page of the Indicator
        <br/>
        <img src="protected/views/help/images/indicator/create-step-4.png" style=";" alt="update-step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="baseline" style="display: block; border-bottom: 1px solid black;">Manage Baselines</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Knowledge Management</strong>&nbsp;module&nbsp;<?php echo ApplicationUtils::generateLink('#enlist', 'here'); ?> and proceed to&nbsp;<?php echo ApplicationUtils::generateLink('#b-step-2', 'Step 2') ?>.
        <br/>
        If you are in the View page of the Indicator, proceed to&nbsp;<?php echo ApplicationUtils::generateLink('#b-step-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="b-step-2"></a>
        Select an indicator to update and click the <strong>Manage</strong> link beside the selected indicator.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-2.png" style=";" alt="baseline-step-2"/>
    </li>
    <li>
        <a name="b-step-3"></a>
        Upon clicking the <strong>Manage</strong> link, you will be redirected to the View page of the selected indicator.
        <br/>
        Click the <strong>Manage Baseline Data</strong> link to manage the Indicator's Baseline data.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-4.png" style=";" alt="baseline-step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Baseline Data</strong>, you will be redirected to the Baseline Data management page.
        <br/>
        To <strong>ADD</strong> a new entry, click <?php echo ApplicationUtils::generateLink('#addBaseline', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing entry, click <?php echo ApplicationUtils::generateLink('#updateBaseline', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing entry, click <?php echo ApplicationUtils::generateLink('#deleteBaseline', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/indicator/manage-baseline.png" style=";" alt="baseline-step-4"/>
    </li>
    <li>DONE</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addBaseline" style="display: block; border-bottom: 1px solid black;">Add Baseline Data</a>
<ol>
    <li>
        Accomplish the input data and click the <strong>Enlist</strong> button to continue.
        <br/>
        <img src="protected/views/help/images/indicator/manage-baseline.png" style=";" alt="b-add-step-1"/>
    </li>
    <li>
        Upon successful validation, input data is then saved in the data source and you are redirected to the Baseline Data management page.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#baseline', 'Back to Manage Baselines'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateBaseline" style="display: block; border-bottom: 1px solid black;">Update Baseline Data</a>
<ol>
    <li>
        Select the baseline data you want to edit by clicking the <strong>Update</strong> link.
        <br/>
        <img src="protected/views/help/images/indicator/manage-baseline.png" style=";" alt="b-upd-step-1"/>
    </li>
    <li>
        Upon clicking the <strong>Update</strong> link, you will be redirection to the Update form of the selected Baseline data.
        <br/>
        <img src="protected/views/help/images/indicator/update-baseline.png" style=";" alt="b-upd-step-1"/>
    </li>
    <li>
        Accomplish the needed fields to be updated. Please bear in mind that the <strong>Covered Year</strong> field is a non-updateable field.
        <br/>
        Click the <strong>Update</strong> button to apply the changes.
    </li>
    <li>
        Upon successful validation, the input data will be reflected in the data source and you are redirected to the Baseline Data management page.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#baseline', 'Back to Manage Baselines'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteBaseline" style="display: block; border-bottom: 1px solid black;">Delete Baseline Data</a>
<ol>
    <li>
        Select the baseline data you want to delete by clicking the <strong>Delete</strong> link.
        <br/>
        <img src="protected/views/help/images/indicator/manage-baseline.png" style=";" alt="b-del-step-1"/>
    </li>
    <li>
        Upon clicking the <strong>Delete</strong> link, the application will prompt for confirmation.
        <br/>
        <img src="protected/views/help/images/indicator/delete-baseline.png" style=";" alt="b-del-step-2"/>
    </li>
    <li>Upon clicking the <strong>Yes</strong> button, the selected baseline data will be deleted in the data source and you will be redirected to the Baseline Data management page</li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#baseline', 'Back to Manage Baselines'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>