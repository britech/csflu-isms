<?php
namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <th colspan="5">Commitments Dashboard</th>
        </tr>
        <tr>
            <th rowspan="2" style="width: 30%;">Member</th>
            <th colspan="3" style="width: 60%;">Status</th>
            <th rowspan="2" style="width: 10%;">Total</th>
        </tr>
        <tr>
            <th style="border-left: 1px solid #bbbbbb; width: 20%;">Pending</th>
            <th style="width: 20%;">Ongoing</th>
            <th style="width: 20%;">Finished</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($accounts as $account):
            if (array_key_exists($account->id, $tableData)):
                $output = $tableData[$account->id];
                ?>
                <tr>
                    <td><?php echo ApplicationUtils::generateLink(array('wig/listCommitments', 'wig'=>$data->id, 'emp'=>$account->id), "{$account->employee->givenName} {$account->employee->lastName}") ?></td>
                    <td style="text-align: center;"><?php echo $output->countPendingCommitments(); ?></td>
                    <td style="text-align: center;"><?php echo $output->countOngoingCommitments(); ?></td>
                    <td style="text-align: center;"><?php echo $output->countFinishedCommitments(); ?></td>
                    <td style="text-align: center;"><?php echo $output->countAll(); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
