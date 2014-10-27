<?php namespace org\csflu\isms\views;?>
<?php
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<script src="protected/js/user/manage.js" type="text/javascript"></script>
<div id="accountList"></div>
<input type="hidden" value="<?php echo $params['employee']?>" id="employee"/>

