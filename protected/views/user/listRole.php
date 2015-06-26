<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;

echo ApplicationUtils::generateLink(array('role/createRole'), 'Add New Security Role', array(
    'class' => 'ink-button green flat',
    'style' => 'margin-bottom: 20px;'));
?>
<?php $this->renderPartial('commons/_notification', array('notif' => $params['notif'])); ?>
<script type="text/javascript" src="protected/js/user/listRole.js"></script>
<div id="securityRoleList"></div>

<div id="delete-role">
    <div id="content" style="overflow: hidden">
        <p id="text">Do you want to continue?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>