<?php namespace org\csflu\isms\views;?>
<?php if(isset($params['notif']) && !empty($params['notif'])):?>
<div class="ink-alert basic info" style="margin-top: 0px;"><?php echo $params['notif'];?></div>
<?php endif;?>
<script src="protected/js/user/manage.js" type="text/javascript"></script>
<div id="accountList"></div>
<input type="hidden" value="<?php echo $params['employee']?>" id="employee"/>

