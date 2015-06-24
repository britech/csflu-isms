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