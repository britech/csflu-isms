<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator;

$form = new ModelFormGenerator(array(
    'action' => array('wig/close'),
    'class' => 'ink-form'
        ));
?>
<link href="assets/flick/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/tag-editor/jquery.tag-editor.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/jquery/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="assets/tag-editor/jquery.tag-editor.js"></script>
<script type="text/javascript" src="protected/js/wig/close.js"></script>
<?php echo $form->startComponent(); ?>
<div class="ink-alert basic info" style="margin-top: 0px;">
    <strong>Important Note:</strong>&nbsp;Fields with * are required.
</div>
<div id="validation-container" class="ink-alert block">
    <h4>Validation errors. Please check your entries</h4>
    <p id="validation-message"></p>
</div>
<?php
if (isset($params['validation']) and ! empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<table class="ink-table bordered">
    <thead>
        <tr>
            <td style="font-weight: bold; text-align: right;">Unit</td>
            <td colspan="2"><?php echo $ubtModel->unit->name; ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;">Committed WIG Timeline</td>
            <td colspan="2"><?php echo "{$sessionModel->startingPeriod->format('M. d, Y')} to {$sessionModel->endingPeriod->format('M. d, Y')}" ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;"><?php echo $form->renderLabel($meetingModel, 'meetingDate', array('required' => true)); ?></td>
            <td colspan="2"><div id="meeting-date-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;"><?php echo $form->renderLabel($meetingModel, 'meetingVenue', array('required' => true)); ?></td>
            <td colspan="2"><?php echo $form->renderTextField($meetingModel, 'meetingVenue', array('style' => 'width: 100%;')); ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;"><?php echo $form->renderLabel($meetingModel, 'meetingTimeStart', array('required' => true)); ?></td>
            <td colspan="2"><div id="meeting-time-start-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;"><?php echo $form->renderLabel($meetingModel, 'meetingTimeEnd', array('required' => true)); ?></td>
            <td colspan="2"><div id="meeting-time-end-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: right;">Actual WIG Timeline</td>
            <td colspan="2"><div id="timeline-input"></div></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="3" style="border-top: 1px solid #bbbbbb; background-color: #000; padding-top: 0px; padding-bottom: 0px;  background-color: #000; color: #FFF;">Commitments Update</th>
        </tr>
        <tr>
            <th style="width: 33%;">Member</th>
            <th style="width: 33%;">Commitments</th>
            <th style="width: 33%;">Status</th>
        </tr>
        <?php
        foreach ($accounts as $account):
            $count = $collatedCommitments[$account->id]->countAll();
            ?>

            <?php if ($count == 0): ?>
                <tr>
                    <td><?php echo "{$account->employee->givenName} {$account->employee->lastName}"; ?></td>
                    <td colspan="2">No Commitments defined</td>
                </tr>
            <?php else: ?>    
                <tr>
                    <td rowspan="<?php echo $collatedCommitments[$account->id]->countAll() ?>"><?php echo "{$account->employee->givenName} {$account->employee->lastName}"; ?></td>
                    <td>
                        <?php
                        foreach ($sessionModel->commitments as $commitment) {
                            if ($commitment->user->id == $account->id) {
                                $firstEntry = $commitment;
                                break;
                            }
                        }
                        ?>
                        <?php echo "{$firstEntry->commitment} ({$firstEntry->translateStatusCode()})"; ?>
                    </td>
                    <td>
                        <?php
                        if (count($firstEntry->commitmentMovements) == 0) {
                            echo "N/A";
                        } else {
                            $firstMovementData = "";
                            foreach ($firstEntry->commitmentMovements as $movement) {
                                $firstMovementData.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
                            }
                            echo nl2br($firstMovementData);
                        }
                        ?>
                    </td>
                </tr>
                <?php foreach ($sessionModel->commitments as $data): ?>
                    <?php if ($data->id != $firstEntry->id && $data->user->id == $account->id): ?>
                        <tr>
                            <td style="border-left: 1px solid #bbb;"><?php echo "{$data->commitment} ({$data->translateStatusCode()})"; ?></td>
                            <td>
                                <?php
                                if (count($data->commitmentMovements) == 0) {
                                    echo "N/A";
                                } else {
                                    $movementData = "";
                                    foreach ($data->commitmentMovements as $movement) {
                                        $movementData.=implode('&nbsp;|&nbsp;', explode('+', $movement->notes)) . "\n";
                                    }
                                    echo nl2br($movementData);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="border-top: 1px solid #bbbbbb; padding-top: 0px; padding-bottom: 0px; background-color: #000; color: #FFF;">Scoreboard Update</th>
        </tr>
        <tr>
            <td style="font-weight: bold;"><?php echo $form->renderLabel($movementModel, 'ubtFigure'); ?></td>
            <td colspan="2"><div id="ubt-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold;"><?php echo $form->renderLabel($movementModel, 'firstLeadMeasureFigure'); ?></td>
            <td colspan="2"><div id="lm1-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold;"><?php echo $form->renderLabel($movementModel, 'secondLeadMeasureFigure'); ?></td>
            <td colspan="2"><div id="lm2-input"></div></td>
        </tr>
        <tr>
            <td style="font-weight: bold;"><?php echo $form->renderLabel($movementModel, 'notes', array('required' => true)); ?></td>
            <td colspan="2"><?php echo $form->renderTextArea($movementModel, 'notes', array('style' => 'width: 100%;')); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <?php echo $form->renderSubmitButton('Close WIG Session', array('class' => 'ink-button blue flat', 'style' => 'margin-left: 0px;')) ?>
            </td>
        </tr>
    </tfoot>
</table>
<?php echo $form->renderHiddenField($meetingModel, 'actualSessionStartDate'); ?>
<?php echo $form->renderHiddenField($meetingModel, 'actualSessionEndDate'); ?>
<?php echo $form->renderHiddenField($meetingModel, 'meetingDate'); ?>
<?php echo $form->renderHiddenField($meetingModel, 'meetingTimeStart'); ?>
<?php echo $form->renderHiddenField($meetingModel, 'meetingTimeEnd'); ?>
<?php echo $form->renderHiddenField($movementModel, 'ubtFigure'); ?>
<?php echo $form->renderHiddenField($movementModel, 'firstLeadMeasureFigure'); ?>
<?php echo $form->renderHiddenField($movementModel, 'secondLeadMeasureFigure'); ?>
<?php echo $form->renderHiddenField($sessionModel, 'id'); ?>
<?php echo $form->renderHiddenField($ubtModel->uom, 'description', array('id' => 'uom-ubt')); ?>
<?php echo $form->renderHiddenField($ubtModel->leadMeasures[0]->uom, 'description', array('id' => 'uom-lm1')); ?>
<?php echo $form->renderHiddenField($ubtModel->leadMeasures[1]->uom, 'description', array('id' => 'uom-lm2')); ?>
<?php echo $form->renderHiddenField($ubtModel, 'startingPeriod', array('id' => 'ubt-start')); ?>
<?php echo $form->renderHiddenField($ubtModel, 'endingPeriod', array('id' => 'ubt-end')); ?>
<?php
echo $form->endComponent();
