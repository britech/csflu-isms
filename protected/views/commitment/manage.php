<?php

namespace org\csflu\isms\views;

use org\csflu\isms\models\ubt\Commitment;

switch ($data->commitmentEnvironmentStatus) {
    case Commitment::STATUS_PENDING:
        $page = "commitment/_entry";
        break;

    case Commitment::STATUS_ONGOING:
        $page = "commitment/_movement";
        break;

    default:
        $page = "";
}

$this->renderPartial($page, $params);
