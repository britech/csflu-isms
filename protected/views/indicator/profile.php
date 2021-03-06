<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\indicator\Indicator;

$indicator = $params['indicator'];
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<script src="protected/js/indicator/profile.js"></script>

<div class="column-group quarter-gutters" style="margin-bottom: 1em;">
    <div class="all-50">
        <span style="font-weight: bold;">Indicator</span>
        <p><?php echo $indicator->description; ?></p>

        <span style="font-weight: bold;">Rationale</span>
        <p><?php echo strlen($indicator->rationale) == 0 ? "Not yet defined" : $indicator->rationale; ?></p>

        <span style="font-weight: bold;">Formula</span>
        <p><?php echo strlen($indicator->formula) == 0 ? "Not yet defined" : $indicator->formula; ?></p>

        <span style="font-weight: bold;">Unit of Measure</span>
        <p><?php echo $indicator->uom->description; ?></p>

        <span style="font-weight: bold;">Source of Data</span>
        <p><?php echo strlen($indicator->dataSource) == 0 ? "Not yet defined" : nl2br(implode("\n", explode("+", $indicator->dataSource))); ?></p>

        <span style="font-weight: bold;">Status - Source of Data</span>
        <p><?php echo Indicator::getDataSourceDescriptionList()[$indicator->dataSourceStatus]; ?></p>

        <?php if ($indicator->dataSourceStatus != Indicator::STAT_AVAILABLE): ?>
            <span style="font-weight: bold;">Date of Availability - Source of Data</span>
            <p><?php echo empty($indicator->dataSourceAvailabilityDate) ? "Not yet defined" : $indicator->dataSourceAvailabilityDate; ?></p>
        <?php endif; ?>
    </div>

    <div class="all-50">
        <span style="font-weight: bold;">Baseline Data</span>
        <div id="baselineTable"></div>
    </div>
    <input type="hidden" id="indicator-id" value="<?php echo $indicator->id; ?>"/>
</div>



