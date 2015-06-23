<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="movementUpdate" style="display: block; border-bottom: 1px solid black;">Initiative Update</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>This feature is only available if the selected <strong>Activity</strong> is flagged as <strong>ONGOING</strong>
</div>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('initiative/manage'), 'Initiative'); ?></strong>
        <br/>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
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
        <img src="protected/views/help/images/initiative/dashboard-step-1.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Upon clicking the <strong>Manage</strong> link, the application will prompt for a period date to display the activities in the given Month and Year.
        <br/>
        <img src="protected/views/help/images/initiative/dashboard-step-2.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Afterwards, you will be redirected to the <strong>Activity Dashboard</strong> which displays the activities for the selected Month and Year.
        <br/>
        Select an <strong>Activity</strong> from the dashboard which is enlisted under the <strong>Ongoing</strong> column.
        <img src="protected/views/help/images/initiative/dashboard-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        You will be then redirected to the activity overview page of the selected Activity.
        <br/>
        Click the <strong>Enlist Movement</strong> link to record the movements of the selected Activity
        <br/>
        <img src="protected/views/help/images/initiative/activity-overview-ongoing.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        Afterwards, you will accomplish the movement update form of the selected Activity. Upon accomplishing the form, click the <strong>Enlist</strong> button to record the movement.
        <br/>
        <img src="protected/views/help/images/initiative/movement.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        Upon committing the movement update to the data source, you will be redirected to the Activity Dashboard of the selected Initiative.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>