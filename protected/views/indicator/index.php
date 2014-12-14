<?php

namespace org\csflu\isms\views;

if (isset($notif) && !empty($notif)) {
    $this->renderPartial('commons/_notification', array('notif' => $notif));
}
?>
<script type="text/javascript" src="protected/js/indicator/index.js"></script>
<div id="indicatorList"></div>