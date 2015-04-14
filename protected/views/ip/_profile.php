<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<div class="ink-alert block info" style="margin-top: 0px;">
    <h4><?php echo "{$account->employee->lastName}, {$account->employee->givenName}"; ?></h4>
    <p>
        <strong style="display: block;">Position</strong>
        <span style="display: block; margin-bottom: 1em;"><?php echo $account->employee->position->name; ?></span>

        <strong style="display: block;">Unit</strong>
        <span style="display: block; margin-bottom: 1em;"><?php echo $account->employee->department->name; ?></span>

        <strong style="display: block; text-align: right; border-top: 1px solid black;">Actions</strong>
        <span style="display: block;">
            <?php echo ApplicationUtils::generateLink(array('commitment/enlist'), 'Enlist Commitments'); ?>
        </span>
        <span style="display: block;">
            <?php echo ApplicationUtils::generateLink(array('ip/report'), 'Generate Scorecard'); ?>
        </span>
    </p>
</div>