<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="enlist" style="display: block; border-bottom: 1px solid black;">Unit Breakthrough Enlistment</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map to enlist the Unit Breakthrough from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Unit Breakthroughs</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" alt="step-2"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Unit Breakthroughs</strong> link, you will be redirected to the directory listing of Initiative. 
        <br/>
        Click the <strong>Add Unit Breakthrough</strong> link.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-1.png" alt="step-3"/>
    </li>
    <li>
        You will be redirected to the enlistment form for Unit Breakthroughs, accomplish the enlistment form and click the <strong>Create</strong> button to enlist the Unit Breakthrough.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-2.png" alt="step-4"/>
    </li>
    <li>
        After successful validation, the new Unit Breakthrough will be enlisted in the data source and you will be redirected to its View page.
        <br/>
        To <strong>UPDATE</strong> the entry data, click <?php echo ApplicationUtils::generateLink('#ubtupd-5', 'here'); ?>.
        <br/>
        To <strong>MANAGE STRATEGY ALIGNMENTS</strong>, click here.
        <br/>
        To <strong>MANAGE LEAD MEASURES</strong>, click here.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-3.png" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="update" style="display: block; border-bottom: 1px solid black;">Update Unit Breakthrough Entry Data</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#enlist', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#ubtupd-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#ubtupd-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Unit Breakthrough, proceed to <?php echo ApplicationUtils::generateLink('#ubtupd-5', 'Step 5') ?>
    </li>
    <li>
        <a name="ubtupd-2"></a>
        Select the strategy map of the Unit Breakthrough from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" alt="step-2"/>
    </li>
    <li>
        <a name="ubtupd-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Unit Breakthroughs</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Unit Breakthroughs</strong> link, you will be redirected to the directory listing of Unit Breakthrough. 
        <br/>
        Click the <strong>View</strong> link beside the Unit Breakthrough you want to update.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-1.png" alt="step-4"/>
    </li>
    <li>
        <a name="ubtupd-5"></a>
        Afterwards, you are redirected to the View page of the selected Unit Breakthrough.
        <br/>
        Click the <strong>Update Entry Data</strong> link to perform entry data update.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-3.png" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the Entry Data update form of the Unit Breakthrough.
        <br/>
        Input the necessary data that needs to be updated and click the <strong>Update</strong> to apply the changes.
        <br/>
        <img src="protected/views/help/images/ubt/update-ubt.png" alt="step-6"/>
    </li>
    <li>Upon successful validation, the updated data will be committed and data source and you will be redirected to the View page of the Unit Breakthrough.</li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
<?php $this->renderPartial('help/_ubt-alignments'); ?>
<?php $this->renderPartial('help/_lead-measures');?>