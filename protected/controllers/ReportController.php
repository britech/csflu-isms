<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl;

/**
 * Description of ReportController
 *
 * @author britech
 */
class ReportController extends Controller {

    private $logger;
    private $initiativeService;
    private $modelLoaderUtil;

    public function __construct() {
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->initiativeService = new InitiativeManagementServiceSimpleImpl();
    }

    public function initiativeUpdate($id, $period) {
        $periodDate = \DateTime::createFromFormat('Y-m', $period);
        $initiative = $this->modelLoaderUtil->loadInitiativeModel($id);

        $this->render('report/initiative-update', array(
            'period' => $periodDate,
            'initiative' => $initiative
        ));
    }

}
