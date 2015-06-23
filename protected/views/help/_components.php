<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageComponent" style="display: block; border-bottom: 1px solid black;">Manage Components</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mcomp-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mcomp-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#mcomp-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mcomp-2"></a>
        Select the strategy map of the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="mcomp-3"></a>
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
        <a name="mcomp-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Manage Components</strong> link to perform management of Component data.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the management page of the Initiative's Components.
        <br/>
        To <strong>ADD</strong> a new Component entry, click <?php echo ApplicationUtils::generateLink('#addComponent', 'here'); ?>
        <br/>
        To <strong>UPDATE</strong> an existing Component entry, click <?php echo ApplicationUtils::generateLink('#updateComponent', 'here'); ?>
        <br/>
        To <strong>DELETE</strong> an existing Component entry, click <?php echo ApplicationUtils::generateLink('#deleteComponent', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/initiative/manage-components.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addComponent" style="display: block; border-bottom: 1px solid black;">Add Component Entry</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Enlist</strong> button to insert the new Component data.
        <br/>
        <img src="protected/views/help/images/initiative/manage-components.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new component data is committed in the data source and you will be redirected to the management page of the Components.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageComponent', 'Back to Manage Components'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateComponent" style="display: block; border-bottom: 1px solid black;">Update Component Entry</a>
<ol>
    <li>
        Click the <strong>Update</strong> link beside the Component entry you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/manage-phases.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the update form of the selected Component entry.
        <br/>
        Change the field values that you want to update and click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/initiative/update-component.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the updated component data is committed in the data source and you will be redirected to the management page of the Components.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageComponent', 'Back to Manage Components'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteComponent" style="display: block; border-bottom: 1px solid black;">Delete Component Entry</a>
<ol>
    <li>
        Click the <strong>Delete</strong> link beside the Component entry you want to delete.
        <br/>
        <img src="protected/views/help/images/initiative/manage-components.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected Component entry.
        <br/>
        <img src="protected/views/help/images/initiative/delete-component.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm deletion of the Component entry
    </li>
    <li>
        Upon successful validation, the component data is removed in the data source and you will be redirected to the management page of the Components.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageComponent', 'Back to Manage Components'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>