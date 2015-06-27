<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="managePerspective" style="display: block; border-bottom: 1px solid black;">Manage Perspectives</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mper-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mper-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="mper-2"></a>
        Select the strategy map to update from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="mper-3"></a>
        Upon selection of the strategy map, you will be redirected to its View page. Click <strong>Manage Perspectives</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        You will be then redirected to the management page of the Perspectives.
        <br/>
        To <strong>ADD</strong> a new perspective, click <?php echo ApplicationUtils::generateLink('#addPerspective', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong> an existing perspective, click <?php echo ApplicationUtils::generateLink('#updatePerspective', 'here'); ?>.
        <br/>
        To <strong>DELETE</strong> an existing perspective, click <?php echo ApplicationUtils::generateLink('#deletePerspective', 'here'); ?>.
        <br/>
        <img src="protected/views/help/images/map/manage-perspective.png" style=";" alt="step-4"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addPerspective" style="display: block; border-bottom: 1px solid black;">Add Perspective</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Add</strong> button to insert the new perspective.
        <br/>
        Please bear in mind that the <strong>Position Order</strong> field value cannot be repeated and limited to numbers <strong>1</strong> to <strong>5</strong>
        <br/>
        <img src="protected/views/help/images/map/manage-perspective.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new perspective is committed in the data source and you will redirected to the management page of the Perspectives.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePerspective', 'Back to Manage Perspectives'); ?>
    &nbsp;|&nbsp;
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updatePerspective" style="display: block; border-bottom: 1px solid black;">Update Perspective Entry</a>
<ol>
    <li>
        Click the <i class="fa fa-edit"></i> icon beside the Perspective that you want to update.
        <br/>
        <img src="protected/views/help/images/map/manage-perspective.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the icon, you will be redirected to the Update Form of the perspective.
        <br/>
        Please bear in mind, that you can only update the description of the perspective.
        <br/>
        To apply the changes, click the <strong>Update</strong> button.
        <br/>
        <img src="protected/views/help/images/map/update-perspective.png" style=";" alt="step-2"/>
    </li>
    <li>
        After successful validation, the updated data will be committed to the data source and you will be redirected to the management page of the Perspective.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePerspective', 'Back to Manage Perspectives'); ?>
    &nbsp;|&nbsp;
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deletePerspective" style="display: block; border-bottom: 1px solid black;">Delete Perspective Entry</a>
<ol>
    <li>
        Click the <i class="fa fa-trash-o"></i> icon beside the Perspective that you want to delete.
        <br/>
        <img src="protected/views/help/images/map/manage-perspective.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the icon, the application will prompt for confirmation to delete the selected perspective.
        <br/>
        <img src="protected/views/help/images/map/delete-perspective.png" style=";" alt="step-2"/>
    </li>
    <li>
        Click <strong>Yes</strong> button to confirm deletion the selected perspective.
    </li>
    <li>
        Upon confirmation, you will be redirected to the management page of the perspectives to reflect the updated list.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePerspective', 'Back to Manage Perspectives'); ?>
    &nbsp;|&nbsp;
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>