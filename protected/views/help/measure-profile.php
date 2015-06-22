<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="create" style="display: block; border-bottom: 1px solid black;">Measure Profile Enlistment</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map to enlist the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profiles</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>Create Measure Profile</strong> link.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-1.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Afterwards, you are redirected to the enlistment form for the new Measure Profile.
        <br/>
        Input the entry data in the enlistment form and click the <strong>Create</strong> button to insert the Measure Profile.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-2.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        Upon successful validation and input data has been committed in the data source, you are redirected to the View page of the newly inserted Measure Profile.
        <br/><br/>
        To <strong>UPDATE</strong> the entry data, click <?php echo ApplicationUtils::generateLink('#mpupd-5', 'here'); ?>
        <br/>
        To <strong>MANAGE LEAD OFFICES</strong>, click <?php echo ApplicationUtils::generateLink('#mlead-5', 'here'); ?>
        <br/>
        To <strong>MANAGE TARGET DATA</strong>, click <?php echo ApplicationUtils::generateLink('#mtarget-5', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="entryUpdate" style="display: block; border-bottom: 1px solid black;">Update Measure Profile Entry Data</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mpupd-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mpupd-3', 'Step 3'); ?>
        <br/>
        If you are in the View page of selected Measure Profile, proceed to <?php echo ApplicationUtils::generateLink('#mpupd-5', 'Step 5') ?>
    </li>
    <li>
        <a name="mpupd-2"></a>
        Select the strategy map of the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="mpupd-3"></a>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profile</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>View</strong> link beside the Measure Profile you want to update.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-1.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        <a name="mpupd-5"></a>
        Afterwards, you are redirected to the View page of the selected Measure Profile.
        <br/>
        Click the <strong>Update Profile</strong> link to perform entry data update.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>
        You will be redirected to the Entry Data update form of the Measure Profile.
        <br/>
        Input the necessary data that needs to be updated and click the <strong>Update</strong> to apply the changes.
        <br/>
        <img src="protected/views/help/images/measure-profile/update-profile.png" style="width: 50%; text-align: center;" alt="step-6"/>
    </li>
    <li>Upon successful validation, the updated data will be committed and data source and you will be redirected to the View page of the Measure Profile.</li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
<?php $this->renderPartial('help/_lead-offices'); ?>
<?php $this->renderPartial('help/_targets'); ?>
<?php $this->renderPartial('help/_mp-movements'); ?>