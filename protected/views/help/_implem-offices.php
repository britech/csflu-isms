<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageOffice" style="display: block; border-bottom: 1px solid black;">Manage Implementing Offices</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#moffice-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#moffice-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#moffice-5', 'Step 5') ?>
    </li>
    <li>
        <a name="moffice-2"></a>
        Select the strategy map of the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="moffice-3"></a>
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
        <a name="moffice-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Manage Implementing Offices</strong> link to perform management of Implementing Offices data.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style=";" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Initiative's Implementing Office.
        <br/>
        To <strong>ADD</strong> a new Implementing Office entry, click <?php echo ApplicationUtils::generateLink('#addOffice', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Implementing Office entry, click <?php echo ApplicationUtils::generateLink('#deleteOffice', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/initiative/manage-offices.png" style=";" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addOffice" style="display: block; border-bottom: 1px solid black;">Add Implementing Office Entry</a>
<ol>
    <li>
        Select departments from the list and click the <strong>Enlist</strong> button to insert the new Implementing Office data.
        <br/>
        <strong>Tip:&nbsp;</strong>You can select <strong>TWO or MORE</strong> departments.
        <br/>
        <img src="protected/views/help/images/initiative/manage-offices.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new implementing office data is committed in the data source and you will be redirected to the management page of the Implementing Offices.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageOffice', 'Back to Manage Implementing Offices'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteOffice" style="display: block; border-bottom: 1px solid black;">Delete Implementing Office Entry</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Implementing Office entry you want to delete.
        <br/>
        <img src="protected/views/help/images/initiative/manage-offices.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected Implementing Office entry.
        <br/>
        <img src="protected/views/help/images/initiative/delete-office.png" style=";" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of Implementing Office entry
    </li>
    <li>
        Upon successful validation, the implementing data is removed in the data source and you will be redirected to the management page of the Implementing Offices.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageOffice', 'Back to Manage Implementing Offices'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>