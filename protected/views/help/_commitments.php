<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manage" style="display: block; border-bottom: 1px solid black;">Managing the Commitment Dashboard</a>
<span style="display: block; margin-top: 10px; text-align: center">
    <strong>The Commitment Lifecycle</strong>
    <br/>
    <img src="protected/views/help/images/ip/commitment-lifecycle.png" alt="life-cycle"/>
</span>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Performance Scorecard</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#man-2', 'Step 2'); ?>
    </li>
    <li>
        <a name="man-2"></a>
        Upon clicking the link, you will be redirected to the Commitments Dashboard page.
        <br/>
        To set a Commitment to <strong>PENDING</strong>, click <?php echo ApplicationUtils::generateLink('#pending', 'here') ?>.
        <br/>
        To set a Commitment to <strong>ONGOING</strong>, click <?php echo ApplicationUtils::generateLink('#ongoing', 'here') ?>.
        <br/>
        To set a Commitment to <strong>FINISHED</strong>, click <?php echo ApplicationUtils::generateLink('#finish', 'here') ?>.
        <img src="protected/views/help/images/ip/create-step-3.png" alt="step-1"/>
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="pending" style="display: block; border-bottom: 1px solid black;">Set a Commitment to Pending</a>
<ol>
    <li>
        Select an Commitment which is flagged any of the following: <strong>ONGOING</strong>, <strong>FINISHED</strong>
        <img src="protected/views/help/images/ip/create-step-3.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Pending</strong> link to change the status of the commitment to <strong>PENDING</strong>
        <img src="protected/views/help/images/ip/overview-ongoing.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to set the selected commitment to <strong>PENDING</strong>.
        <img src="protected/views/help/images/ip/commitment-pending.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm the status update.
    </li>
    <li>
        The selected activity will be set to <strong>PENDING</strong> and you will be redirected to the overview page of the Commitment.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manage', 'Back to Managing the Commitment Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="ongoing" style="display: block; border-bottom: 1px solid black;">Set a Commitment to Ongoing</a>
<ol>
    <li>
        Select an Commitment which is flagged any of the following: <strong>PENDING</strong>, <strong>FINISHED</strong>
        <img src="protected/views/help/images/ip/create-step-3.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Ongoing</strong> link to change the status of the commitment to <strong>ONGOING</strong>
        <img src="protected/views/help/images/ip/update-commitment.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to set the selected commitment to <strong>ONGOING</strong>.
        <img src="protected/views/help/images/ip/commitment-ongoing.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm the status update.
    </li>
    <li>
        The selected activity will be set to <strong>ONGOING</strong> and you will be redirected to the overview page of the Commitment.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manage', 'Back to Managing the Commitment Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="finish" style="display: block; border-bottom: 1px solid black;">Set a Commitment to Finished</a>
<ol>
    <li>
        Select an Commitment which is flagged any of the following: <strong>PENDING</strong>, <strong>ONGOING</strong>
        <img src="protected/views/help/images/ip/create-step-3.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the overview page of the selected activity.
        <br/>
        Click the <strong>Set to Finished</strong> link to change the status of the commitment to <strong>FINISHED</strong>
        <img src="protected/views/help/images/ip/update-commitment.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to movements record page of the selected Commitment.
        <br/>
        Accomplish the movement record form and click the <strong>Add</strong> button to insert the movement record and update its status.
        <img src="protected/views/help/images/ip/commitment-finish.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        The selected activity will be set to <strong>FINISHED</strong> and you will be redirected to the Commitment Dashboard.
    </li>
    <li>DONE.</li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manage', 'Back to Managing the Commitment Dashboard'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>