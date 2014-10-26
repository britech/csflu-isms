<?php

namespace org\csflu\isms\views;

if (isset($params['notif']) && !empty($params['notif'])):?>
<div style="margin-top:10px;">
    <?php $this->renderPartial('commons/_notification', array('notif' => $params['notif'])); ?>
</div>
<?php endif;?>
<script src="protected/js/uom/index.js" type="text/javascript"></script>
<div id="uomList"></div>
