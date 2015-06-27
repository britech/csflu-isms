<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="movementUpdate" style="display: block; border-bottom: 1px solid black;">Record Commitment Movement</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can only perform this action on an <strong>ONGOING</strong> commitment.
</div>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Performance Scorecard</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#mov-2', 'Step 2'); ?>
    </li>
    <li>
        <a name="mov-2"></a>
        Upon clicking the link, you will be redirected to the Commitments Dashboard page.
        <br/>
        Click the commitment under the <strong>ONGOING</strong> column to record the movement update.
        <img src="protected/views/help/images/ip/create-step-3.png" alt="step-1"/>
    </li>
    <li>
        The application will load the commitments overview page with the movement update form.
        <br/>
        Accomplish the form and click the <strong>Add</strong> button to record the movement update.
        <img src="protected/views/help/images/ip/overview-ongoing.png" alt="step-1"/>
    </li>
    <li>
        The movement update data will be saved in the data source and you will be redirected to the Commitment Dashboard page.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>