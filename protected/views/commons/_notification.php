<?php

if(isset($params['notif']) && !empty($params['notif'])):?>
<div class="ink-alert basic <?php echo $params['notif']['class'];?>" style="margin-top: 0px;">
    <?php echo $params['notif']['message'];?>
</div>
<?php
endif;

