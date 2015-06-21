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
        Upon clicking the <strong>Manage Measure Profile</strong> link, you will be redirected to the directory listing of Measure Profiles. 
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
        To <strong>UPDATE</strong> the strategy map, click <?php echo ApplicationUtils::generateLink('#mpupd-3', 'here'); ?>
        <br/>
        To <strong>MANAGE LEAD OFFICES</strong> of the strategy map, click <?php echo ApplicationUtils::generateLink('#mlead-3', 'here'); ?>
        <br/>
        To <strong>MANAGE TARGET DATA</strong> of the strategy map, click <?php echo ApplicationUtils::generateLink('#mtarget-3', 'here'); ?>
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>