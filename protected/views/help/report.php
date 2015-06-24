<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="measureProfile" style="display: block; border-bottom: 1px solid black;">Measure Profile</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map of the Measure Profile from the directory listing.
        <img src="protected/views/help/images/map/create-step-1.png" alt="step-2"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profiles</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>View</strong> link beside the measure profile that you want to generate the report.
        <img src="protected/views/help/images/measure-profile/create-step-1.png" alt="step-4"/>
    </li>
    <li>
        Afterwards, you are redirected to the View page of the selected Measure Profile.
        <br/>
        Click the <strong>Generate Measure Profile</strong> link to perform report generation.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-3.png" alt="step-5"/>
    </li>
    <li>
        Upon clicking the link, the application will process the request and create the report file.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="scorecardUpdate" style="display: block; border-bottom: 1px solid black;">Scorecard Update</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map of the Measure Profile from the directory listing.
        <img src="protected/views/help/images/map/create-step-1.png" alt="step-2"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" alt="step-3"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Measure Profiles</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>Manage Movements</strong> link beside the measure profile that you want to generate the report.
        <img src="protected/views/help/images/measure-profile/create-step-1.png" alt="step-4"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for a time period to display the movement of the selected Measure Profile.
        <img src="protected/views/help/images/measure-profile/mov-step-1.png" alt="step-5"/>
    </li>
    <li>
        You will be then redirected to the movement overview page of the selected Measure Profile under the selected Date Period.
        <br/>
        Click the <strong>Generate Scorecard Update</strong> link to generate the report.
        <img src="protected/views/help/images/measure-profile/mov-step-2.png" alt="step-6"/>
    </li>
    <li>
        Upon clicking the link, the application will process the request and create the report file.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>