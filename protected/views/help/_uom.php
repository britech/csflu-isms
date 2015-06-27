<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageUom" style="display: block; border-bottom: 1px solid black;">Manage Unit Of Measures</a>
<ol>
    <li>
        From the application's Home page, click <strong>Settings&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('uom/index'), 'Unit of Measures'); ?></strong>.
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        You will be then redirected to the management page of Unit of Measure entries.
        <br/>
        To <strong>ADD</strong>, click <?php echo ApplicationUtils::generateLink('#addUom', 'here'); ?>.
        <br/>
        To <strong>UPDATE</strong>, click <?php echo ApplicationUtils::generateLink('#updateUom', 'here'); ?>.
        <img src="protected/views/help/images/admin/manage-uom.png" alt="step-1"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="addUom" style="display: block; border-bottom: 1px solid black;">Unit of Measure Enlistment</a>
<ol>
    <li>
        Click the <strong>Add UOM</strong> link to begin enlistment of a unit of measure entry.
        <img src="protected/views/help/images/admin/manage-uom.png" alt="step-1"/>
    </li>
    <li>
        The application will load the unit of measure entry enlistment form.
        <br/>
        Complete the input form and click the <strong>Add</strong> button to insert the new unit of measure entry.
        <img src="protected/views/help/images/admin/add-uom.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the new unit of measure entry is saved in the data source and you will be redirected to the management page of the unit of measures.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageUom', 'Back to Manage Unit of Measures'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateUom" style="display: block; border-bottom: 1px solid black;">Update Unit of Measure Entry</a>
<ol>
    <li>
        Click the <strong>Update Data</strong> link beside the unit of measure entry you want to update.
        <img src="protected/views/help/images/admin/manage-uom.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will load the unit of measure entry update form.
        <br/>
        Update the field value and click the <strong>Update</strong> button to apply the changes.
        <img src="protected/views/help/images/admin/update-uom.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, the updated unit of measure entry is saved in the data source and you will be redirected to the management page of the unit of measures.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageUom', 'Back to Manage Unit of Measures'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>