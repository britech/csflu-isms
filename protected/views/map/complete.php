<?php

namespace org\csflu\isms\views;

$strategyMap = $params['strategyMap'];
$perspectives = $params['perspectives'];
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<script type="text/javascript" src="protected/js/map/complete.js"></script>
<div class="column-group quarter-gutters">
    <div class="all-100">
        <div class="ink-alert block info align-center" style="margin-bottom: 0px;">
            <h4 style="padding: 0px; letter-spacing: 10px;"><?php echo strtoupper($strategyMap->name); ?></h4>
            <p style="margin: 0px;"><?php echo $strategyMap->visionStatement; ?></p>
        </div>
    </div>

    <div class="all-30">
        <div class="ink-alert block info" style="margin-top: 0px;">
            <h4 style="padding: 0px; text-align:center;">MISSION</h4>
            <?php $mission = explode("+", $strategyMap->missionStatement); ?>
            <p style="margin: 0px 0px 0px 10px;">
                <?php
                if (count($mission) < 1) {
                    echo "&lt;Not yet defined&gt;";
                } else {
                    foreach ($mission as $text) {
                        echo "-&nbsp;{$text}<br/>";
                    }
                }
                ?>
            </p>
        </div>
        <div class="ink-alert block info">
            <h4 style="padding: 0px; text-align:center;">VALUES</h4>
            <?php $values = explode("+", $strategyMap->valuesStatement); ?>
            <p style="margin: 0px 0px 0px 10px;">
                <?php
                if (count($values) < 1) {
                    echo "&lt;Not yet defined&gt;";
                } else {
                    foreach ($values as $text) {
                        echo "-&nbsp;{$text}<br/>";
                    }
                }
                ?>
            </p>
        </div>
    </div>

    <div class="all-70">
        <table class="ink-table bordered" style="margin-top: 0px;">
            <tbody>
                <?php foreach ($perspectives as $perspective): ?>
                    <tr>
                        <th title="Click to update the perspective, <?php echo $perspective->description;?> or add an objective" style="width: 20%; background-color: black; color:white; cursor: pointer;" id="pers-<?php echo $perspective->id; ?>"><?php echo $perspective->description; ?></th>
                        <td>
                            <?php
                            if (empty($strategyMap->objectives) || count($strategyMap->objectives) < 1) {
                                echo "&lt;Not yet defined&gt;";
                            } else {
                                foreach ($strategyMap->objectives as $objective) {
                                    if ($objective->perspective->id == $perspective->id) {
                                        echo '*&nbsp;' . $objective->description;
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

