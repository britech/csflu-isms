<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="create" style="display: block; border-bottom: 1px solid black;">Commitments Enlistment</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can add commitments if there is an <strong>OPEN</strong> WIG Session.
</div>
<ol>
    <li>
        From your application's Home page, click <strong>My Profile&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('ip/index'), 'Performance Scorecard'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Commitments Dashboard page.
        <br/>
        Click the <strong>Enlist Commitments</strong> link to perform the enlistment.
        <img src="protected/views/help/images/ip/create-step-1.png" alt="step-1"/>
    </li>
    <li>
        The application will then load the enlistment form. Input the commitments to be inserted and click the <strong>Enlist</strong> to enlist the new commitments.
        <br/>
        <strong>Tip:&nbsp;</strong>You can add multiple commitments at once.
        <img src="protected/views/help/images/ip/create-step-2.png" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the commitments will be inserted in the data source and you will redirected to the Commitments Dashboard page.
        <br/>
        <strong>Note:&nbsp;</strong>Commitments that are newly inserted is set to <strong>PENDING</strong>. To manage commitment status updates, click <?php echo ApplicationUtils::generateLink('#manage', 'here'); ?>
        <img src="protected/views/help/images/ip/create-step-3.png" alt="step-2"/>
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="update" style="display: block; border-bottom: 1px solid black;">Update Commitment Entry</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can only perform this action for commitments that are <strong>PENDING</strong>
</div>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Performance Scorecard</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#cupd-2', 'Step 2'); ?>
    </li>
    <li>
        <a name="cupd-2"></a>
        Upon clicking the link, you will be redirected to the Commitments Dashboard page.
        <br/>
        Click the commitment that you want to update.
        <img src="protected/views/help/images/ip/create-step-3.png" alt="step-1"/>
    </li>
    <li>
        The application will then load the management page of the selected Commitment with the update description form. 
        Update the commitment description if necessary and click the <strong>Update</strong> to apply the changes.
        <br/>
        <img src="protected/views/help/images/ip/update-commitment.png" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated commitment entry is saved in the data source and you will be redirected to the management page of the updated commitment.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>