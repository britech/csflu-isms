<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\util\ModelFormGenerator as Form;

$form = new Form(array(
    'action' => array($model->isNew() ? 'theme/insert' : 'theme/update'),
    'class' => 'ink-form',
    'hasFieldset' => true
        ));
?>
<script type="text/javascript" src="protected/js/perspective/theme.js"></script>
<?php
if (isset($params['notif']) && !empty($params['notif'])) {
    $this->renderPartial('commons/_notification', array('notif' => $params['notif']));
}
echo $form->startComponent();
echo $form->constructHeader($model->isNew() ? 'Add Theme' : 'Update Theme', array('style' => 'margin-bottom:10px;'));
if (isset($params['validation']) && !empty($params['validation'])) {
    $this->viewWarningPage('Validation error/s. Please check your entries', implode('<br/>', $params['validation']));
}
?>
<div class="control-group column-group half-gutters">
    <?php echo $form->renderLabel($model, 'description', array('class' => 'all-20 align-right')); ?>
    <div class="all-80">
        <div id="description-input"></div>
        <?php
        echo $form->renderHiddenField($model, 'description', array('id' => 'description'));
        echo $form->renderHiddenField($mapModel, 'id');
        if ($model->isNew()) {
            echo $form->renderSubmitButton('Add', array('class' => 'ink-button green flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        } else {
            echo $form->renderHiddenField($model, 'id');
            echo $form->renderSubmitButton('Update', array('class' => 'ink-button blue flat', 'style' => 'margin-top: 1em; margin-left: 0px;'));
        }
        ?>
    </div>
</div>

<?php echo $form->endComponent(); ?>
<div class="column-group quarter-gutters">
    <div class="all-100"></div>
    <div class="all-50 push-center">
        <table class="ink-table bordered alternating">
            <thead>
                <tr>
                    <th style="width: 80%;">Theme</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($themes) > 0): ?>
                    <?php foreach ($themes as $theme): ?>
                        <tr>
                            <td><?php echo $theme->description; ?></td>
                            <td style="text-align: center;">
                                <?php echo ApplicationUtils::generateLink(array('theme/update', 'id' => $theme->id), '<i class="fa fa-save">&nbsp;</i>') ?>
                                &nbsp;|&nbsp;
                                <?php echo ApplicationUtils::generateLink('#', '<i class="fa fa-trash-o">&nbsp;</i>', array('id' => 'del-' . $theme->id)) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No Themes Defined</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="deleteTheme">
    <div id="deleteThemeContent" style="overflow: hidden">
        <p id="text"></p>
        <div class="all-50 push-center align-center">
            <button class="ink-button red flat" id="accept">Yes</button>
            <button class="ink-button green flat" id="deny">No</button>
        </div>
    </div>
</div>

