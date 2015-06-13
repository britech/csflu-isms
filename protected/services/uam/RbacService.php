<?php

namespace org\csflu\isms\service\uam;

use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\exceptions\ServiceException;

/**
 *
 * @author britech
 */
interface RbacService {

    /**
     * Validates the selected SecurityRole entity if it is allowed to view the selected component under the selected module
     * @param SecurityRole $securityRole
     * @param string $moduleCode
     * @param string $actionCode
     * @return boolean
     * @throws ServiceException
     */
    public function validateRole(SecurityRole $securityRole, $moduleCode, $actionCode);
}
