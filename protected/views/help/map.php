<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="create" style="display: block; border-bottom: 1px solid black;">Strategy Map Enlistment</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('map/index'), 'Strategy Management'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" style="width: 50%; text-align: center;" alt="index-page"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected to the Strategy Map directory page.
        <br/>
        Click the <?php echo ApplicationUtils::generateLink(array('map/create'), 'Create a Strategy Map', array('style' => 'font-weight: bold;')); ?> link.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-1"/>
    </li>
    <li>
        Afterwards, you will be redirected to the form page of the <strong>NEW</strong> Strategy Map's entry data
        <br/>
        <img src="protected/views/help/images/map/create-step-2.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>Click the <strong>Create</strong> button to insert the Strategy Map.</li>
    <li>
        Upon successful validation, the input data is reflected in the data source
        <br/>
        and you are redirected to the View page of the newly inserted Strategy Map.
        <br/><br/>
        To <strong>UPDATE</strong> the strategy map, click here
        <br/>
        To <strong>MANAGE PERSPECTIVES</strong> of the strategy map, click here
        <br/>
        To <strong>MANAGE OBJECTIVES</strong> of the strategy map, click here
        <br/>
        To <strong>MANAGE THEMES</strong> of the strategy map, click here
        <br/>
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>