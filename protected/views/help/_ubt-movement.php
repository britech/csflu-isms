<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<a name="movementUpdate" style="display: block; border-bottom: 1px solid black;">Record Unit Breakthrough Movements</a>
<ol>
    <li>
        From your application's Home page, click <strong>Modules&nbsp;&gt;&nbsp;<?php echo ApplicationUtils::generateLink(array('ubt/manage'), 'Unit Breakthrough'); ?></strong>
        <img src="protected/views/help/images/commons/index.png" alt="index-page"/>
    </li>
    <li>
        You will be then redirected the assigned Unit Breakthroughs under the unit where your account belongs.
        <br/>
        Click the <strong>UBT Movements</strong> link beside the unit breakthrough you selected to record the Unit Breakthrough Movement.
        <img src="protected/views/help/images/ubt/create-wig-1.png" alt="step-1"/>
    </li>
    <li>
        The application will then display the list of movements for the selected Unit Breakthrough.
        <br/>
        Click the <strong>Add Movement</strong> link to render the movement entry form.
        <img src="protected/views/help/images/ubt/mov-step-1.png" alt="step-2"/>
    </li>
    <li>
        Upon clicking the link, you will be redirected the movement entry form.
        <br/>
        Accomplish the entry form and click the <strong>Add</strong> button to insert the movement data.
        <img src="protected/views/help/images/ubt/mov-step-2.png" alt="step-3"/>
    </li>
    <li>
        After successful validation, the movement data will be saved in the data source and you will redirected to movement log page.
    </li>
    <li>
        DONE.
    </li>
</ol>
<?php echo ApplicationUtils::generateLink('#top', 'Back to Top', array('style' => 'display: block; margin-bottom: 50px;')); ?>
