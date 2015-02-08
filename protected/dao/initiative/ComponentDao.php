<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\initiative\Phase;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface ComponentDao {
    
    /**
     * @param Phase $phase
     * @return Component[]
     * @throws DataAccessException
     */
    public function listComponents(Phase $phase);
    
    /**
     * @param Component $component
     * @param Phase $phase
     * @throws DataAccessException
     */
    public function addComponent(Component $component, Phase $phase);
    
    /**
     * @param Component $component
     * @param Phase $phase
     * @throws DataAccessException
     */
    public function updateComponent(Component $component, Phase $phase);
    
    /**
     * @param String $id
     * @throws DataAccessException
     */
    public function deleteComponent($id);
}
