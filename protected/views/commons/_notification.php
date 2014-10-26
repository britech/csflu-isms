<?php

if(isset($params['notif']) && !empty($params['notif'])):?>
<div class="ink-alert basic <?php echo $params['notif']['class'];?>">
    <?php echo $params['notif']['message'];?>
</div>
<?php
endif;

