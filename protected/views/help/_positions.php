<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="managePosition" style="display: block; border-bottom: 1px solid black;">Manage Positions</a>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('position/index'), 'Positions'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        You will be then redirected to the management page of Position entries.
        <br/>
        To <strong>ADD</strong>, click <?php echo ApplicationUtils::generateLink('#addPosition', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong>, click <?php echo ApplicationUtils::generateLink('#updatePosition', 'here'); ?>.
        <img src="protected/views/help/images/admin/manage-positions.png" alt="step-1"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addPosition" style="display: block; border-bottom: 1px solid black;">Position Enlistment</a>
<ol>
    <li>
        Input the position title and click <strong>Enlist</strong> button to insert the new position entry.
        <img src="protected/views/help/images/admin/manage-positions.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new position entry is saved in the data source and you will be redirected to the management page of the positions.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePosition', 'Back to Manage Positions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updatePosition" style="display: block; border-bottom: 1px solid black;">Update Position Entry</a>
<ol>
    <li>
        Click the <strong>Update Position</strong> link beside the position entry you want to update.
        <img src="protected/views/help/images/admin/manage-positions.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will load the position entry update form.
        <br/>
        Update the field value and click the <strong>Update</strong> button to apply the changes.
        <img src="protected/views/help/images/admin/update-positions.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the updated position entry is saved in the data source and you will be redirected to the management page of the positions.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#managePosition', 'Back to Manage Positions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>