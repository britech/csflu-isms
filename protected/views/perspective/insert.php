<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\util\ApplicationUtils;

$perspectives = $params['perspectiveList'];

$form = new Form(array(
    'action' => array('map/insertPerspective'),
    'class' => 'ink-form'
        ));
?>
<script type="text/javascript" src="protected/js/perspective/form.js"></script>
<div id="validation-container"></div>
<?php
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
?>
<?php echo $form->startComponent(); ?>
<div class="ink-alert block info">
    <h4>Important Notes</h4>
    <p>
        -&nbsp;Fields with * are required.<br/>
        -&nbsp;Highest position order is 1 (one) and lowest position order is 5 (five). The position order will be used on the displaying the strategy map's perspectives.
    </p>
</div>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Perspective&nbsp;*', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <div id="description-input"></div>
    </div>
</div>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Position Order&nbsp;*', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <div id="positionOrder"></div>
        <?php echo $form->renderSubmitButton("Add", array('class' => 'ink-button green flat', 'style' => 'margin-left: 0px; margin-top: 1em')); ?>
    </div>
    <?php echo $form->renderHiddenField('Perspective[description]', array('id'=>'description')); ?>
    <?php echo $form->renderHiddenField('Perspective[positionOrder]', array('id' => 'position-order')); ?>
    <?php echo $form->renderHiddenField('StrategyMap[id]', array('value' => $params['id'], 'id' => 'strategy-id')); ?>
</div>
<?php echo $form->endComponent(); ?>
<div class="column-group quarter-gutters">
    <div class="all-50 push-center">
        <table class="ink-table bordered alternating">
            <thead>
                <tr>
                    <th>Perspective</th>
                    <th style="width: 20%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($perspectives) < 1): ?>
                    <tr>
                        <td colspan="2">No perspectives defined</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($perspectives as $perspective): ?>
                        <tr>
                            <td><?php echo $perspective->description; ?></td>
                            <td style="text-align:center;">
                                <?php echo ApplicationUtils::generateLink(array('map/updatePerspective', 'id' => $perspective->id), '<i class="fa fa-edit">&nbsp;</i>') ?>
                                &nbsp;|&nbsp;
                                <?php echo ApplicationUtils::generateLink(array('map/confirmDeletePerspective', 'id' => $perspective->id), '<i class="fa fa-trash-o">&nbsp;</i>') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
