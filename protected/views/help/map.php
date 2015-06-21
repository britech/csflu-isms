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
    <li>After inputting the necessary data, click the <strong>Create</strong> button to insert the Strategy Map.</li>
    <li>
        Upon successful validation and input data has been committed in the data source, you are redirected to the View page of the newly inserted Strategy Map.
        <br/><br/>
        To <strong>UPDATE</strong> the strategy map, click <?php echo ApplicationUtils::generateLink('#supd-3', 'here'); ?>
        <br/>
        To <strong>MANAGE PERSPECTIVES</strong> of the strategy map, click <?php echo ApplicationUtils::generateLink('#mper-3', 'here'); ?>
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

<a name="entryUpdate" style="display: block; border-bottom: 1px solid black;">Update Strategy Map Entry Data</a>
<ol>
    <li>
        If you are in the application's Home page, check the location of the <strong>Strategy Management</strong> module <?php echo ApplicationUtils::generateLink('#create', 'here') ?> and proceed to <?php echo ApplicationUtils::generateLink('#supd-2', 'Step 2'); ?>
        <br/>
        If you are in the View page of selected Strategy Map, proceed to <?php echo ApplicationUtils::generateLink('#supd-3', 'Step 3'); ?>
    </li>
    <li>
        <a name="supd-2"></a>
        Select the strategy map to update from the directory listing.
        <br/>
        <img src="protected/views/help/images/map/create-step-1.png" style="width: 50%; text-align: center;" alt="step-2"/>
    </li>
    <li>
        <a name="supd-3"></a>
        Upon selection of the strategy map, you will be redirected to its View page. Click <strong>Update Entry Data</strong> link.
        <img src="protected/views/help/images/map/create-step-3.png" style="width: 50%; text-align: center;" alt="step-3"/>
    </li>
    <li>
        The application will then redirect you to the Update Entry Data form of the Strategy Map.
        <br/>
        <img src="protected/views/help/images/map/update-strategy-map.png" style="width: 50%; text-align: center;" alt="step-4"/>
    </li>
    <li>After accomplishing the update data, click the <strong>Update</strong> button to apply the changes.</li>
    <li>Upon successful validation, the updated data will be committed and data source and you will be redirected to the View page of the Strategy Map.</li>
    <li>DONE.</li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
<?php $this->renderPartial('help/_perspectives');
