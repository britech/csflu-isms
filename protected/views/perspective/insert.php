<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ModelFormGenerator as Form;
use org\csflu\isms\util\ApplicationUtils;

$form = new Form(array(
    'action' => array('perspective/insert'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/perspective/insert.js"></script>

<div class="column-group quarter-gutters">
    <div class="all-50">
        <?php echo $form->startComponent(); ?>
        <?php echo $form->constructHeader('Add Perspective');?>
        <div class="ink-alert block info">
            <h4>Important Notes</h4>
            <p>
                -&nbsp;Fields with * are required.<br/>
                -&nbsp;Highest position order is 1 (one) and lowest position order is 5 (five). The position order will be used on the displaying the strategy map's perspectives.
            </p>
        </div>
        <div id="validation-container"></div>
        <?php
        if (isset($params['notif']) && !empty($params['notif'])) {
            $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
        }
        if (isset($params['validation']) && !empty($params['validation'])) {
            $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
        }
        ?>
        <div class="control-group column-group quarter-gutters">
            <?php echo $form->renderLabel($model, 'description', array('class' => 'all-30 align-right', 'required' => true)); ?>
            <div class="control all-70">
                <div id="description-input"></div>
            </div>
        </div>
        <div class="control-group column-group quarter-gutters">
            <?php echo $form->renderLabel($model, 'positionOrder', array('class' => 'all-30 align-right', 'required' => true)); ?>
            <div class="control all-70">
                <div id="positionOrder"></div>
                <?php echo $form->renderSubmitButton("Add", array('class' => 'ink-button green flat', 'style' => 'margin-left: 0px; margin-top: 1em')); ?>
            </div>
            <?php echo $form->renderHiddenField($model, 'description', array('id' => 'description')); ?>
            <?php echo $form->renderHiddenField($model, 'positionOrder', array('id' => 'position-order')); ?>
            <?php echo $form->renderHiddenField($mapModel, 'id', array('id' => 'strategy-id')); ?>
        </div>
        <?php echo $form->endComponent(); ?>
    </div>
    <div class="all-50">
        <table class="ink-table bordered alternating">
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th>Perspective</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($perspectiveList) < 1): ?>
                    <tr>
                        <td colspan="3">No perspectives defined</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($perspectiveList as $perspective): ?>
                        <tr>
                            <td style="text-align:center;">
                                <?php echo $perspective->positionOrder; ?>
                            </td>
                            <td id="description-<?php echo $perspective->id ?>"><?php echo $perspective->description; ?></td>
                            <td style="text-align:center;">
                                <?php echo ApplicationUtils::generateLink(array('perspective/update', 'id' => $perspective->id), '<i class="fa fa-edit">&nbsp;</i>') ?>
                                &nbsp;|&nbsp;
                                <?php echo ApplicationUtils::generateLink("#", '<i class="fa fa-trash-o">&nbsp;</i>', array('id' => "del-{$perspective->id}")) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="deletePerspective">
    <div id="deleteThemeContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>
