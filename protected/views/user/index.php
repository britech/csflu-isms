<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils as ApplicationUtils;
?>
<script src="protected/js/user/index.js" type="text/javascript"></script>
<?php echo ApplicationUtils::generateLink(array('user/createAccount'), 'Create Account', array('class' => 'ink-button green flat', 'style' => 'margin-bottom: 10px;')) ?>
&nbsp;<span style="display: inline-block; margin-bottom: 10px;">for new users</span>
<?php
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<div id="employeeList"></div>