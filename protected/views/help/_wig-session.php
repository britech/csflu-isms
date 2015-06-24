<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="manageWig" style="display: block; border-bottom: 1px solid black;">Managing WIG Sessions</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('ubt/manage'), 'Unit Breakthrough'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        You will be then redirected the assigned Unit Breakthroughs under the unit where your account belongs.
        <br/>
        Click the <strong>Manage WIG Sessions</strong> link beside the unit breakthrough you selected to manage the WIG Sessions.
        <img src="protected/views/help/images/ubt/create-wig-1.png" alt="step-1"/>
    </li>
    <li>
        Afterwards, the page will display the enlisted WIG Sessions under the selected unit breakthrough.
        <br/>
        To <strong>CREATE</strong> a new WIG Session, click <?php echo ApplicationUtils::generateLink('#openWig', 'here') ?>.
        <br/>
        To <strong>UPDATE TIMELINE</strong> of the WIG Session, click <?php echo ApplicationUtils::generateLink('#updateTimeline', 'here') ?>.
        <br/>
        To <strong>DELETE</strong> the WIG Session, click <?php echo ApplicationUtils::generateLink('#deleteWig', 'here') ?>.
        <br/>
        To <strong>CLOSE</strong> a WIG Session, click <?php echo ApplicationUtils::generateLink('#closeWig', 'here') ?>.
        <img src="protected/views/help/images/ubt/create-wig-2.png" alt="step-2"/>
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>

<a name="openWig" style="display: block; border-bottom: 1px solid black;">Create a new WIG Session</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You cannot create a new WIG Session if there's an <strong>OPEN</strong> WIG Session.
</div>
<ol>
    <li>
        Select the desired starting and ending date of the WIG Session, click the <strong>Enlist</strong> button to create the WIG Session.
        <img src="protected/views/help/images/ubt/create-wig-2.png" alt="step-1"/>
    </li>
    <li>
        Upon successful validation, you will be redirected the overview page of the New WIG Session.
        <img src="protected/views/help/images/ubt/create-wig-3.png" alt="step-2"/>
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageWig', 'Back to Managing WIG Sessions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="updateTimeline" style="display: block; border-bottom: 1px solid black;">Update WIG Session Timeline</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can only perform this action on an <strong>OPEN</strong> WIG Session.
</div>
<ol>
    <li>
        Click the <strong>Update Timeline</strong> link to update the WIG Session timeline.
        <img src="protected/views/help/images/ubt/create-wig-3.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt an update form.
        <br/>
        Update the timeline and click the <strong>Update</strong> button to apply the changes.
        <img src="protected/views/help/images/ubt/update-wig-timeline.png" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the updated timeline will be committed in the data source and you will redirected to the overview page of the WIG Session.
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageWig', 'Back to Managing WIG Sessions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="deleteWig" style="display: block; border-bottom: 1px solid black;">Delete WIG Session</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can only perform this action on an <strong>OPEN</strong> WIG Session with <strong>EMPTY</strong> commitments of the unit members.
</div>
<ol>
    <li>
        Click the <strong>Delete WIG Session</strong> link beside the WIG Session you want to delete.
        <img src="protected/views/help/images/ubt/create-wig-2.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, the application will prompt for confirmation to delete the selected WIG Session.
        <br/>
        <img src="protected/views/help/images/ubt/delete-wig.png" alt="step-2"/>
    </li>
    <li>
        Click the <strong>Yes</strong> button to confirm removal of the selected WIG Session
    </li>
    <li>
        The selected WIG Session will be deleted in the data source and you will be redirected to the management page of WIG Sessions. 
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageWig', 'Back to Managing WIG Sessions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>

<a name="closeWig" style="display: block; border-bottom: 1px solid black;">Closing the WIG Session</a>
<div class="ink-alert basic info">
    <strong>Important Note:&nbsp;</strong>You can only perform this action on an <strong>OPEN</strong> WIG Session with commitments of the unit members.
</div>
<ol>
    <li>
        Click the <strong>Close WIG Session</strong> link to close the WIG Session.
        <img src="protected/views/help/images/ubt/close-wig-1.png" alt="step-1"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the WIG Meeting template form.
        <br/>
        Accomplish the form and click the <strong>Close WIG Session</strong> button to complete the WIG Session
        <img src="protected/views/help/images/ubt/close-wig-2.png" alt="step-2"/>
    </li>
    <li>
        Upon successful validation, the WIG meeting data and Unit Breakthrough movement will be saved in the data source and you will be redirected to the overview page of the WIG Session with the Movement updates.
         <img src="protected/views/help/images/ubt/close-wig-3.png" alt="step-3"/>
    </li>
    <li>
        DONE.
    </li>
</ol>
<span style="display: block; margin-bottom: 50px;">
    <?php echo ApplicationUtils::generateLink('#manageWig', 'Back to Managing WIG Sessions'); ?>
    &nbsp;|&nbsp;
    <?php echo ApplicationUtils::generateLink('#top', 'Back to Top'); ?>
</span>