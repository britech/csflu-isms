<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\uam\ModuleAction;
?>
<div class="ink-grid">
    <div class="all-100 push-center">
        <table class="ink-table bordered alternating">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $actions = $params['actions'];
                $module = new ModuleAction();

                foreach ($actions as $action):
                    ?>
                    <tr>
                        <td><?php echo ModuleAction::getModules()[$action->module->module]; ?></td>
                        <td>
                            <?php
                            foreach ($module->getAllowableActionByModule($action->module->module) as $actionCode => $actionName) {
                                if (in_array($actionCode, explode('/', $action->module->actions))) {
                                    echo $actionName . '<br/>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
