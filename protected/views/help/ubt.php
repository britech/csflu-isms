<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="enlist" style="display: block; border-bottom: 1px solid black;">Unit Breakthrough Enlistment</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Select the strategy map to enlist the Unit Breakthrough from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        You will be then redirected to the View page of the selected Strategy Map. Click the <strong>Manage Unit Breakthroughs</strong> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        Upon clicking the <strong>Manage Unit Breakthroughs</strong> link, you will be redirected to the directory listing of Initiative. 
        <br/>
        Click the <strong>Add Unit Breakthrough</strong> link.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-1.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        You will be redirected to the enlistment form for Unit Breakthroughs, accomplish the enlistment form and click the <strong>Create</strong> button to enlist the Unit Breakthrough.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-2.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>
        After successful validation, the new Unit Breakthrough will be enlisted in the data source and you will be redirected to its View page.
        <br/>
        To <strong>UPDATE</strong> the entry data, click here.
        <br/>
        To <strong>MANAGE STRATEGY ALIGNMENTS</strong>, click here.
        <br/>
        To <strong>MANAGE LEAD MEASURES</strong>, click here.
        <br/>
        <img src="protected/views/help/images/ubt/create-step-3.png" style="width: 50%; text-align: center;" alt="step-5"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
