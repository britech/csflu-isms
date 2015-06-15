<?php

namespace org\csflu\isms\views;

use org\csflu\isms\util\FormGenerator as Form;
use org\csflu\isms\models\uam\ModuleAction;

$model = $params['model'];

$form = new Form(array(
    'action' => array('role/create'),
    'class' => 'ink-form'));

echo $form->startComponent();
?>
<script src="protected/js/user/createRole.js" type="text/javascript"></script>
<h3 style="text-align: right;">Add Security Role</h3>
<?php if (isset($params['notif']) && !empty($params['notif'])): ?>
    <div class="ink-alert basic info" style="margin-top: 0px;"><?php echo $params['notif']; ?></div>
<?php endif; ?>
<div id="validation-error" class="ink-alert block">
    <h4>Validation Error/s. Please check your entries</h4>
    <p id="content"></p>
</div>
<div class="control-group column-group quarter-gutters">
    <?php echo $form->renderLabel('Description', array('class' => 'all-20 align-right')); ?>
    <div class="control all-80">
        <?php echo $form->renderTextField('SecurityRole[description]') ?>
    </div>
</div>

<?php echo $form->renderLabel('Allowable Actions', array('style' => 'text-align: center; display: block;')); ?>
<div class="column-group quarter-gutters">
    <div class="all-33">
        <table>
            <tbody>
                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_SMAP); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_SMAP) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_SMAP . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr><td>&nbsp;</td></tr>

                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_SCARD); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_SCARD) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_SCARD . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr><td>&nbsp;</td></tr>

                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_INITIATIVE); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_INITIATIVE) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_INITIATIVE . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="all-33">
        <table>
            <tbody>
                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_UBT); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_UBT) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_UBT . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr><td>&nbsp;</td></tr>

                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_IP); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_IP) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_IP . '][]', $name, array('value' => $value, 'checked' => true));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr><td>&nbsp;</td></tr>

                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_KM); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_KM) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_KM . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="all-33">
        <table>
            <tbody>
                <tr>
                    <td style="text-align: left; font-style: italic;"><?php echo $model->getModuleName(ModuleAction::MODULE_SYS); ?></td>
                </tr>
                <?php foreach ($model->getAllowableActionByModule(ModuleAction::MODULE_SYS) as $value => $name): ?>
                    <tr>
                        <td>
                            <?php
                            echo $form->renderCheckBox('AllowableAction[module][' . ModuleAction::MODULE_SYS . '][]', $name, array('value' => $value));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr><td>&nbsp;</td></tr>

                <tr>
                    <td>
                        <?php echo $form->renderSubmitButton('Add Security Role', array('class' => 'ink-button green flat', 'style' => 'margin-left: 0px;')) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
echo $form->endComponent();
