<?php

namespace org\csflu\isms\dao\initiative;

use org\csflu\isms\models\initiative\ActivityMovement;
use org\csflu\isms\models\initiative\Activity;
use org\csflu\isms\exceptions\DataAccessException;

/**
 *
 * @author britech
 */
interface ActivityMovementDao {

    /**
     * @param Activity $activity
     * @return ActivityMovement[]
     * @throws DataAccessException
     */
    public function listMovements(Activity $activity);

    /**
     * @param Activity $activity
     * @throws DataAccessException
     */
    public function recordMovements(Activity $activity);
}
