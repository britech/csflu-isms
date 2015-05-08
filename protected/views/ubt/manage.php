<script src="protected/js/ubt/manage.js" type="text/javascript"></script>
<div class="column-group quarter-gutters">
    <div class="all-10">
        <button class="ink-button blue flat" id="refresh">Refresh</button>
    </div>
    <div class="all-90">
        <?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
    </div>
    <div class="all-100">
        <div id="ubtList-<?php echo $unit ?>" style="margin-bottom: 1em;"></div>
    </div>
</div>
