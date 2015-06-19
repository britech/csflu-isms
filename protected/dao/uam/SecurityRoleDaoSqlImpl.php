<?php

namespace org\csflu\isms\dao\uam;

use org\csflu\isms\core\ConnectionManager;
use org\csflu\isms\dao\uam\SecurityRoleDao;
use org\csflu\isms\exceptions\DataAccessException;
use org\csflu\isms\models\uam\SecurityRole;
use org\csflu\isms\models\uam\AllowableAction;
use org\csflu\isms\models\uam\ModuleAction;

/**
 * Description of SecurityRoleDaoSqlImpl
 *
 * @author britech
 */
class SecurityRoleDaoSqlImpl implements SecurityRoleDao {

    public function getSecurityRoleData($id) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT utype_id, type_desc FROM user_types WHERE utype_id=:id');
            $dbst->execute(array('id' => $id));

            $securityRole = new SecurityRole();

            while ($data = $dbst->fetch()) {
                list($securityRole->id, $securityRole->description) = $data;
            }

            $securityRole->allowableActions = $this->getLinkedActionsBySecurityRole($securityRole);

            return $securityRole;
        } catch (\PDException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function listSecurityRoles() {
        try {
            $db = ConnectionManager::getConnectionInstance();

            $dbst = $db->prepare('SELECT utype_id, type_name, type_desc FROM user_types ORDER BY type_desc ASC');
            $dbst->execute();

            $roles = array();

            while ($data = $dbst->fetch()) {
                $role = new SecurityRole();
                list($role->id, $role->name, $role->description) = $data;
                array_push($roles, $role);
            }

            return $roles;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    /**
     * 
     * @param SecurityRole $securityRole
     * @throws DataAccessException
     */
    public function manageLinkedActions(SecurityRole $securityRole) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $actions = $this->getLinkedActionsBySecurityRole($securityRole);
            $db->beginTransaction();

            if (count($actions) > 0) {
                /**
                 * delete the inserted user actions of the selected security role
                 */
                $dbstClean = $db->prepare('DELETE FROM user_actions WHERE type_ref=:type');
                $dbstClean->execute(array(
                    'type' => $securityRole->id
                ));
            }

            foreach ($securityRole->allowableActions as $allowableAction) {
                $dbst = $db->prepare('INSERT INTO user_actions VALUES(:module, :actions, :type)');
                $dbst->execute(array(
                    'module' => $allowableAction->module->module,
                    'actions' => $allowableAction->module->actions,
                    'type' => $securityRole->id
                ));
            }

            $db->commit();
        } catch (\PDException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function updateRoleDescription($securityRole) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('UPDATE user_types SET type_desc=:description WHERE utype_id=:id');
            $dbst->execute(array('description' => $securityRole->description, 'id' => $securityRole->id));

            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    /**
     * 
     * @param SecurityRole $securityRole
     * @return AllowableAction[]
     * @throws DataAccessException
     */
    public function getLinkedActionsBySecurityRole($securityRole) {
        try {
            $db = ConnectionManager::getConnectionInstance();
            $dbst = $db->prepare('SELECT module_code, actions FROM user_actions WHERE type_ref=:id');
            $dbst->execute(array('id' => $securityRole->id));

            $allowableActions = array();
            while ($data = $dbst->fetch()) {
                $allowableAction = new AllowableAction();
                $allowableAction->module = new ModuleAction();
                list($allowableAction->module->module, $allowableAction->module->actions) = $data;
                array_push($allowableActions, $allowableAction);
            }
            return $allowableActions;
        } catch (\PDOException $ex) {
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function enlistSecurityRole($securityRole) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            $dbst = $db->prepare('INSERT INTO user_types(type_desc) VALUES(:description)');
            $dbst->execute(array('description' => $securityRole->description));
            $id = $db->lastInsertId();

            $db->commit();
            $securityRole->id = $id;
            $this->manageLinkedActions($securityRole);
            return $id;
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

    public function deleteSecurityRole($securityRole) {
        $db = ConnectionManager::getConnectionInstance();
        try {
            $db->beginTransaction();

            //cleanup linked actions
            $dbstActions = $db->prepare('DELETE FROM user_actions WHERE type_ref=:ref');
            $dbstActions->execute(array('ref' => $securityRole->id));

            // cleanup linked security roles
            $dbstUsers = $db->prepare('DELETE FROM user_main WHERE type_ref=:ref');
            $dbstUsers->execute(array('ref' => $securityRole->id));

            // delete the security role
            $dbstRole = $db->prepare('DELETE FROM user_types WHERE utype_id=:id');
            $dbstRole->execute(array('id' => $securityRole->id));

            $db->commit();
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new DataAccessException($ex->getMessage());
        }
    }

}
