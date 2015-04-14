<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\models\ubt\Commitment;
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <th style="width: 33%;">Pending</th>
            <th style="width: 33%;">Ongoing</th>
            <th style="width: 33%;">Finished</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($commitments) == 0): ?>
            <tr>
                <th style="text-align: left;" colspan="3">No Commitments defined.</th>
            </tr>
        <?php else: ?>
            <?php foreach ($commitments as $commitment): ?>
                <tr>
                    <?php if ($commitment->commitmentEnvironmentStatus == Commitment::STATUS_PENDING): ?>
                        <td><?php echo ApplicationUtils::generateLink(array('commitment/manage', 'id' => $commitment->id), $commitment->commitment); ?></td>
                    <?php else: ?>
                        <td>&nbsp;</td>
                    <?php endif; ?>

                    <?php if ($commitment->commitmentEnvironmentStatus == Commitment::STATUS_ONGOING): ?>
                        <td><?php echo ApplicationUtils::generateLink(array('commitment/manage', 'id' => $commitment->id), $commitment->commitment); ?></td>
                    <?php else: ?>
                        <td>&nbsp;</td>
                    <?php endif; ?>

                    <?php if ($commitment->commitmentEnvironmentStatus == Commitment::STATUS_FINISHED): ?>
                        <td><?php echo ApplicationUtils::generateLink(array('commitment/manage', 'id' => $commitment->id), $commitment->commitment); ?></td>
                    <?php else: ?>
                        <td>&nbsp;</td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>