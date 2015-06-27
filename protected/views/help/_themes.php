<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageTheme" style="display: block; border-bottom: 1px solid black;">Manage Themes</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mtheme-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mtheme-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="mtheme-2"></a>
        Select the strategy map to update from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="mtheme-3"></a>
        Upon selection of the strategy map, you will be redirected to its View page. Click <strong>Manage Themes</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        You will be then redirected to the management page of the Themes.
        <br/>
        To <strong>ADD</strong> a new theme, click <?php echo ApplicationUtils::generateLink('#addTheme', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong> an existing theme, click <?php echo ApplicationUtils::generateLink('#updateTheme', 'here'); ?>.
        <br/>
        To <strong>DELETE</strong> an existing theme, click <?php echo ApplicationUtils::generateLink('#deleteTheme', 'here'); ?>.
        <br/>
        <img src="protected/views/help/images/map/manage-theme.png" style=";" alt="step-4"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addTheme" style="display: block; border-bottom: 1px solid black;">Add Themes</a>
<ol>
    <li>
        Accomplish the input data form and click the <strong>Add</strong> button to insert the new theme.
        <br/>
        <img src="protected/views/help/images/map/manage-theme.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new theme is committed in the data source and you will redirected to the management page of the Themes.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTheme', 'Back to Manage Themes'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateTheme" style="display: block; border-bottom: 1px solid black;">Update Theme Entry</a>
<ol>
    <li>
        Click the <i class="fa fa-edit"></i> icon beside the Theme that you want to update.
        <br/>
        <img src="protected/views/help/images/map/manage-theme.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the icon, you will be redirected to the Update Form of the theme.
        <br/>
        After inputting the updated data, click the <strong>Update</strong> button to apply the changes.
        <br/>
        <img src="protected/views/help/images/map/update-theme.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated theme will be committed in the data source and you will be redirected to management page of the Themes.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTheme', 'Back to Manage Themes'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteTheme" style="display: block; border-bottom: 1px solid black;">Delete Theme Entry</a>
<ol>
    <li>
        Click the <i class="fa fa-trash-o"></i> icon beside the Theme that you want to delete.
        <br/>
        <img src="protected/views/help/images/map/manage-theme.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the icon, the application will prompt for confirmation to delete the selected theme.
        <br/>
        <img src="protected/views/help/images/map/delete-theme.png" style=";" alt="step-2"/>
    </li>
    <li>
        Click <strong>Yes</strong> button to confirm deletion the selected theme.
    </li>
    <li>
        Upon confirmation, you will be redirected to the management page of the themes to reflect the updated list.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageTheme', 'Back to Manage Themes'); ?>
    &nbsp;|&nbsp;
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>