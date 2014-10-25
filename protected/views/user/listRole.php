<?php

namespace org\csflu\isms\views; 
use org\csflu\isms\util\ApplicationUtils;

echo ApplicationUtils::generateLink(array('role/createRole'), 
        'Add New Security Role', 
        array(
            'class'=>'ink-button green flat',
            'style'=>'margin-bottom: 20px;'));
?>
<?php if(isset($params['notif']) && !empty($params['notif'])):?>
<div class="ink-alert basic info"><?php echo $params['notif'];?></div>
<?php endif;?>
<script type="text/javascript" src="protected/js/user/listRole.js"></script>
<div id="securityRoleList"></div>