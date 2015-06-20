<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="enlist" style="display: block; border-bottom: 1px solid black;">Indicator Enlistment</a>
<ol>
    <li>
        From the application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('km/index'), 'Knowledge Management'); ?></strong>.
        <br/>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Knowledge Management module page.
        <br/>
        Click the <?php echo ApplicationUtils::generateLink(array('km/indicators'), 'Indicators') ?> link
        <br/>
        <img src="protected/views/help/images/indicator/create-step-1.png" style="width: 50%; text-align: center;" alt="create-step-1"/>
    </li>
    <li>
        Click the <?php echo ApplicationUtils::generateLink(array('indicator/enlist'), 'Enlist an Indicator') ?> link.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-2.png" style="width: 50%; text-align: center;" alt="create-step-2"/>
    </li>
    <li>
        Upon clicking the link, it will display an entry data form for the <strong>NEW</strong> indicator to be enlisted.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-3.png" style="width: 50%; text-align: center;" alt="create-step-3"/>
    </li>
    <li>
        Upon completion of the form, the inputs are validated and saved in the data source;<br/>
        afterwards, you will be redirected to the View page of the newly enlisted indicator.
        <br/>
        <img src="protected/views/help/images/indicator/create-step-4.png" style="width: 50%; text-align: center;" alt="create-step-4"/>
    </li>
    <li>
        To update the Indicator data, click <?php echo ApplicationUtils::generateLink('#update', 'here'); ?>
        <br/>
        To manage the Baselines data, click <?php echo ApplicationUtils::generateLink('#manageBaselines', 'here'); ?>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>