<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\service\uam\RbacService;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\exceptions\ServiceException;

/**
 * Description of RbacServiceImpl
 *
 * @author britech
 */
class RbacServiceImpl implements RbacService {

    public function validateRole(SecurityRole $securityRole, $moduleCode, $actionCode) {
        $allowableActions = $securityRole->allowableActions;

        if (count($allowableActions) == 0) {
            throw new ServiceException("No actions defined for role: {$securityRole->description}");
        }

        foreach ($allowableActions as $allowableAction) {
            $actions = explode("/", $allowableAction->module->actions);
            if ($moduleCode == $allowableAction->module->module && in_array($actionCode, $actions)) {
                return true;
            }
        }
        return false;
    }

}
