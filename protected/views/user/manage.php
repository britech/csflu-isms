<?php

namespace org\csflu\isms\views; ?>
<?php
$this->renderPartial('commons/_notification', array('notif' => $params['notif']));
?>
<script src="protected/js/user/manage.js" type="text/javascript"></script>
<div id="accountList"></div>
<input type="hidden" value="<?php echo $employee->id ?>" id="employee"/>

<div id="reset-password">
    <div id="content" style="overflow: hidden">
        <p id="text-reset">Do you want to continue?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-reset">Yes</button>
            <button class="ink-button green flat" id="deny-reset">No</button>
        </div>
    </div>
</div>