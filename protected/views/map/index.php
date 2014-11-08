<?php

namespace org\csflu\isms\views;

if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<script src="protected/js/map/index.js"></script>
<div id="strategyMapList"></div>
