<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="movement" style="display: block; border-bottom: 1px solid black;">Measure Profile Movement</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mov-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#mov-4', 'Step 4'); ?>
    </li>
    <li>
        <a name="mov-2"></a>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map to enlist the Measure Profile from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style=";" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Measure Profiles</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style=";" alt="step-2"/>
    </li>
    <li>
        <a name="mov-4"></a>
        Upon clicking the <strong>Manage Measure Profiles</strong> link, you will be redirected to the directory listing of Measure Profiles. 
        <br/>
        Click the <strong>Manage Movements</strong> link beside the Measure Profile that will be inserted with a movement data.
        <br/>
        <img src="protected/views/help/images/measure-profile/create-step-1.png" style=";" alt="step-3"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for an input period to insert the movement data.
        <br/>
        <img src="protected/views/help/images/measure-profile/mov-step-1.png" style=";" alt="step-4"/>
    </li>
    <li>
        After selecting an input period, you will be redirected to the movement overview of the selected Measure Profile for the selected date period.
        <br/>
        Click the <strong>Update Scorecard Movement</strong> link to insert the movement data.
        <br/>
        <img src="protected/views/help/images/measure-profile/mov-step-2.png" style=";" alt="step-5"/>
    </li>
    <li>
        You will redirected to the input form for the movement data.
        <br/>
        Accomplish the input form and click the <strong>Enlist</strong> button to insert the movement data.
        <br/>
        <img src="protected/views/help/images/measure-profile/mov-step-3.png" style=";" alt="step-6"/>
    </li>
    <li>
        After successful validation, you will be redirected to the movement overview of the Measure Profile.
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>