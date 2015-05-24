<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\controllers\support\ModelLoaderUtil;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

class ScorecardController extends Controller {

    private $mapService;
    private $scorecardService;
    private $modelLoaderUtil;
    private $logger;

    public function __construct() {
        $this->checkAuthorization();
        $this->scorecardService = new ScorecardManagementService();
        $this->modelLoaderUtil = ModelLoaderUtil::getInstance($this);
        $this->logger = \Logger::getLogger(__CLASS__);
        $this->layout = 'column-2';
    }

    public function listLeadMeasures() {
        $this->validatePostData(array('map', 'readonly'));
        $map = $this->getFormData('map');
        $readOnly = boolval($this->getFormData('readonly'));
        $strategyMap = $this->loadMapModel($map);

        $leadMeasures = $this->scorecardService->listMeasureProfiles($strategyMap);
        $data = array();
        foreach ($leadMeasures as $leadMeasure) {

            if ($readOnly) {
                $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View');
            } else {
                $actions = ApplicationUtils::generateLink(array('measure/view', 'id' => $leadMeasure->id), 'View') . " | " . ApplicationUtils::generateLink('#', 'Manage Movements', array('id' => "movement-{$leadMeasure->id}"));
            }

            array_push($data, array(
                'id' => $leadMeasure->id,
                'perspective' => $leadMeasure->objective->perspective->positionOrder . '&nbsp;-&nbsp;' . $leadMeasure->objective->perspective->description,
                'objective' => $leadMeasure->objective->description,
                'indicator' => $leadMeasure->indicator->description,
                'action' => $actions
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    private function loadMapModel($id) {
        return $this->modelLoaderUtil->loadMapModel($id);
    }

}
