<?php $this->renderPartial('commons/_notification', array('notif' => $notif)); ?>
<table class="ink-table bordered">
    <tbody>
        <tr>
            <th style="text-align: left; width: 20%">Activity</th>
            <td><?php echo "{$data->activityNumber} - {$data->title}"; ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Status</th>
            <td><?php echo $data->translateStatusCode(); ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Target</th>
            <td><?php echo $data->descriptionOfTarget; ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Indicator</th>
            <td><?php echo $data->indicator; ?></td>
        </tr>
        <?php if (!empty(floatval($data->budgetAmount))): ?>
            <tr>
                <th style="text-align: left;">Budget Allocated</th>
                <td><?php echo "PHP " . number_format($data->budgetAmount, 2); ?></td>
            </tr>
            <tr>
                <th style="text-align: left;">Remaining Budget</th>
                <td><?php echo "PHP " . number_format($data->computeRemainingBudget(), 2); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>