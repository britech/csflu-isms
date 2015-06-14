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
        <p>Do you want to continue?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-reset">Yes</button>
            <button class="ink-button green flat" id="deny-reset">No</button>
        </div>
    </div>
</div>

<div id="disable-account">
    <div id="content" style="overflow: hidden">
        <p>Do you want to continue?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-disable">Yes</button>
            <button class="ink-button green flat" id="deny-disable">No</button>
        </div>
    </div>
</div>

<div id="activate-account">
    <div id="content" style="overflow: hidden">
        <p>Do you want to continue?</p>
        <div class="all-50 push-center align-center">
            <button class="ink-button green flat" id="accept-activate">Yes</button>
            <button class="ink-button red flat" id="deny-activate">No</button>
        </div>
    </div>
</div>

<div id="delete-account">
    <div id="content" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept-delete">Yes</button>
            <button class="ink-button green flat" id="deny-delete">No</button>
        </div>
    </div>
</div>