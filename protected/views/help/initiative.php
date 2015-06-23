<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="create" style="display: block; border-bottom: 1px solid black;">Initiative Enlistment</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map to enlist the Initiative from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Initiatives</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Initiatives</strong> link, you will be redirected to the directory listing of Initiative. 
        <br/>
        Click the <strong>Create an Initiative</strong> link.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-1.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Afterwards, you are redirected to the enlistment form for the new Initiative.
        <br/>
        Input the entry data in the enlistment form and click the <strong>Create</strong> button to insert the Initiative.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-2.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        Upon successful validation and input data has been committed in the data source, you are redirected to the View page of the newly inserted Measure Profile.
        <br/>
        <br/>
        To <strong>UPDATE</strong> the entry data, click <?php echo ApplicationUtils::generateLink('#iniupd-5', 'here'); ?>
        <br/>
        To <strong>MANAGE IMPLEMENTING OFFICES</strong>, click <?php echo ApplicationUtils::generateLink('#moffice-5', 'here'); ?>
        <br/>
        To <strong>MANAGE PHASES</strong>, click <?php echo ApplicationUtils::generateLink('#mphase-5', 'here'); ?>
        <br/>
        To <strong>MANAGE COMPONENTS</strong>, click <?php echo ApplicationUtils::generateLink('#mcomp-5', 'here'); ?>
        <br/>
        To <strong>MANAGE ACTIVITIES</strong>, click <?php echo ApplicationUtils::generateLink('#mact-5', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="entryUpdate" style="display: block; border-bottom: 1px solid black;">Update Initiative Entry Data</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#iniupd-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#iniupd-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Initiative, proceed to <?php echo ApplicationUtils::generateLink('#iniupd-5', 'Step 5') ?>
    </li>
    <li>
        <a name="iniupd-2"></a>
        Select the strategy map of the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="iniupd-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Initiatives</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Initiatives</strong> link, you will be redirected to the directory listing of Initiatives. 
        <br/>
        Click the <strong>View</strong> link beside the Initiative you want to update.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-1.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        <a name="iniupd-5"></a>
        Afterwards, you are redirected to the View page of the selected Initiative.
        <br/>
        Click the <strong>Update Entry Data</strong> link to perform entry data update.
        <br/>
        <img src="protected/views/help/images/initiative/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the Entry Data update form of the Initiative.
        <br/>
        Input the necessary data that needs to be updated and click the <strong>Update</strong> to apply the changes.
        <br/>
        <img src="protected/views/help/images/initiative/update-initiative.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>Upon successful validation, the updated data will be committed and data source and you will be redirected to the View page of the Initiative.</li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
<?php $this->renderPartial('help/_implem-offices'); ?>
<?php $this->renderPartial('help/_phases'); ?>
<?php $this->renderPartial('help/_components'); ?>
<?php $this->renderPartial('help/_activities'); ?>