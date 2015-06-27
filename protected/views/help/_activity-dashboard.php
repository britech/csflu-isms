<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="activityDashboard" style="display: block; border-bottom: 1px solid black;">Managing the Activity Dashboard</a>
<span style="display: block; text-align: center;">
    <strong style="display: block; margin-top: 10px;">The Activity Life Cycle</strong>
    <img src="protected/views/help/images/initiative/activity-lifecycle.png" style=";" alt="life-cycle"/>    
</span>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('initiative/manage'), 'Initiative'); ?></strong>
        <br/>
        <img src="protected/views/help/images/commons/index.png" style=";" alt="index-page"/>
    </li>
    <li>
        You will be redirected to the initiatives that are assigned to the unit or department your account belongs.
        <br/>
        Click the <strong>Manage</strong> link beside the Initiative that you want to perform Initiative Movement update.
        <br/>
        <br/>
        <strong>Important Note:&nbsp;</strong>The initiatives that are listed are based on the underlying unit or department of the your account. 
        The unit or department should be assigned as an <strong>Implementing Office</strong> in order to perform Initiative update.
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-1.png" style=";" alt="step-1"/>
    </li>
    <li>
        Upon clicking the <strong>Manage</strong> link, the application will prompt for a period date to display the activities in the given Month and Year.
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-2.png" style=";" alt="step-2"/>
    </li>
    <li>
        Afterwards, you will be redirected to the <strong>Activity Dashboard</strong> which displays the activities for the desired Month and Year.
        <br/>
        To set an Activity to <strong>PENDING</strong>, click <?php echo ApplicationUtils::generateLink('#pendingActivity', 'here'); ?>.
        <br/>
        To set an Activity to <strong>ONGOING</strong>, click <?php echo ApplicationUtils::generateLink('#ongoingActivity', 'here'); ?>.
        <br/>
        To set an Activity to <strong>FINISHED</strong>, click <?php echo ApplicationUtils::generateLink('#finishActivity', 'here') ?>.
        <br/>
        To set an Activity to <strong>DISCONTINUED</strong>, click <?php echo ApplicationUtils::generateLink('#stopActivity', 'here'); ?>.
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style=";" alt="step-3"/>
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="pendingActivity" style="display: block; border-bottom: 1px solid black;">Set an Activity to Pending</a>
<ol>
    <li>
        Select an Activity which is flagged any of the following: <strong>ONGOING</strong>, <strong>FINISHED</strong>, <strong>DISCONTINUED</strong>
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style=";" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Pending</strong> link to change the status of the activity to <strong>PENDING</strong>
        <br/>
        <img src="protected/views/help/images/initiative/activity-overview-ongoing.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to set the selected activity to <strong>PENDING</strong>.
        <br/>
        <img src="protected/views/help/images/initiative/activity-pending.png" style=";" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm the status update.
    </li>
    <li>
        The selected activity will be set to <strong>PENDING</strong> and you will be redirected to the Activity Dashboard of the selected initiative.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#activityDashboard', 'Back to Manage Activity Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="ongoingActivity" style="display: block; border-bottom: 1px solid black;">Set an Activity to Ongoing</a>
<ol>
    <li>
        Select an Activity which is flagged any of the following: <strong>PENDING</strong>, <strong>FINISHED</strong>, <strong>DISCONTINUED</strong>
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style=";" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Ongoing</strong> link to change the status of the activity to <strong>ONGOING</strong>
        <br/>
        <img src="protected/views/help/images/initiative/activity-overview-pending.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to set the selected activity to <strong>ONGOING</strong>.
        <br/>
        <img src="protected/views/help/images/initiative/activity-ongoing.png" style=";" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm the status update.
    </li>
    <li>
        The selected activity will be set to <strong>ONGOING</strong> and you will be redirected to the Activity Dashboard of the selected initiative.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#activityDashboard', 'Back to Manage Activity Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="finishActivity" style="display: block; border-bottom: 1px solid black;">Set an Activity to Finished</a>
<ol>
    <li>
        Select an Activity which is flagged any of the following: <strong>PENDING</strong>, <strong>ONGOING</strong>
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style=";" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Finished</strong> link to change the status of the activity to <strong>Finished</strong>
        <br/>
        <img src="protected/views/help/images/initiative/activity-overview-pending.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the movement update page.
        <br/>
        Accomplish the update form and click the <strong>Enlist</strong> button to record the movement update of the selected activity
        <br/>
        <img src="protected/views/help/images/initiative/activity-finished.png" style=";" alt="step-3"/>
    </li>
    <li>
        The selected activity will be set to <strong>FINISHED</strong> and you will be redirected to the Activity Dashboard of the selected initiative.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#activityDashboard', 'Back to Manage Activity Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="stopActivity" style="display: block; border-bottom: 1px solid black;">Set an Activity to Discontinued</a>
<ol>
    <li>
        Select an Activity which is flagged any of the following: <strong>PENDING</strong>, <strong>ONGOING</strong>
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style=";" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Discontinued</strong> link to change the status of the activity to <strong>Discontinued</strong>
        <br/>
        <img src="protected/views/help/images/initiative/activity-overview-pending.png" style=";" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the movement update page.
        <br/>
        Accomplish the update form and click the <strong>Enlist</strong> button to record the movement update and notes of the selected activity
        <br/>
        <img src="protected/views/help/images/initiative/activity-discontinued.png" style=";" alt="step-3"/>
    </li>
    <li>
        The selected activity will be set to <strong>DISCONTINUED</strong> and you will be redirected to the Activity Dashboard of the selected initiative.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#activityDashboard', 'Back to Manage Activity Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>