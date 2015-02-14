<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\models\initiative\Component;
use org\csflu\isms\exceptions\DataAccessException;
/**
 *
 * @author britech
 */
interface ActivityDao {
    
    /**
     * @param Component $component
     * @return Activity[]
     * @throws DataAccessException
     */
    public function listActivities(Component $component);
    
    /**
     * @param String $id
     * @return Activity
     * @throws DataAccessException
     */
    public function getActivity($id);
    
    /**
     * @param Activity $activity
     * @param Component $component
     * @throws DataAccessException
     */
    public function addActivity(Activity $activity, Component $component);
}
