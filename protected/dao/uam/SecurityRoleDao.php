<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface SecurityRoleDao {

    /**
     * @return SecurityRole[]
     * @throws DataAccessException
     */
    public function listSecurityRoles();
    
    /**
     * @param Integer $id
     * @return SecurityRole
     * @throws DataAccessException
     */
    public function getSecurityRoleData($id);
    
    /**
     * @param SecurityRole $securityRole
     * @throws DataAccessException
     */
    public function updateRoleDescription($securityRole);
    
    /**
     * @param SecurityRole $securityRole
     * @throws DataAccessException
     */
    public function manageLinkedActions($securityRole);
    
    /**
     * @param SecurityRole
     * @throws DataAccessException
     */
    public function getLinkedActionsBySecurityRole($securityRole);
    
    /**
     * @param SecurityRole $securityRole
     * @return String
     * @throws DataAccessException
     */
    public function enlistSecurityRole($securityRole);
}
