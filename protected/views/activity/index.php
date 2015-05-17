<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\util\ApplicationUtils;
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <th style="text-align: right;">Initiative</th>
            <th style="text-align: left;" colspan="3"><?php echo $data->title; ?></th>
        </tr>
        <tr>
            <th style="text-align: right;">Covered Period</th>
            <th style="text-align: left;" colspan="3"><?php echo $date->format('F - Y') ?></th>
        </tr>
        <tr>
            <th style="width: 25%;">Pending</th>
            <th style="width: 25%;">Ongoing</th>
            <th style="width: 25%;">Finished</th>
            <th style="width: 25%;">Discontinued</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data->phases as $phase): ?>
            <?php foreach ($phase->components as $component): ?>
                <?php foreach ($component->activities as $activity): ?>
                    <?php if ($activity->startingPeriod == $date): ?>
                        <tr>
                            <?php if ($activity->activityEnvironmentStatus == Activity::STATUS_PENDING): ?>
                                <td><?php echo ApplicationUtils::generateLink(array('activity/manage', 'id' => $activity->id, 'period' => $date->format('Y-m')), $activity->title); ?></td>
                            <?php else: ?>  
                                <td>&nbsp;</td>
                            <?php endif; ?>
                            <?php if ($activity->activityEnvironmentStatus == Activity::STATUS_ONGOING): ?>
                                <td><?php echo ApplicationUtils::generateLink(array('activity/manage', 'id' => $activity->id, 'period' => $date->format('Y-m')), $activity->title); ?></td>
                            <?php else: ?>
                                <td>&nbsp;</td>
                            <?php endif; ?>
                            <?php if ($activity->activityEnvironmentStatus == Activity::STATUS_FINISHED): ?>
                                <td><?php echo ApplicationUtils::generateLink(array('activity/manage', 'id' => $activity->id, 'period' => $date->format('Y-m')), $activity->title); ?></td>
                            <?php else: ?>
                                <td>&nbsp;</td>
                            <?php endif; ?>
                            <?php if ($activity->activityEnvironmentStatus == Activity::STATUS_DROPPED): ?>
                                <td><?php echo ApplicationUtils::generateLink(array('activity/manage', 'id' => $activity->id, 'period' => $date->format('Y-m')), $activity->title); ?></td>
                            <?php else: ?>
                                <td>&nbsp;</td>
                            <?php endif; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

