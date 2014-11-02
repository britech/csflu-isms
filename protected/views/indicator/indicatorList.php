<?php

namespace org\csflu\isms\views;

if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<script type="text/javascript" src="protected/js/indicator/indicatorList.js"></script>
<div id="indicatorList"></div>