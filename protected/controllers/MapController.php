<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\core\Model;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\exceptions\ValidationException;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapService;
use org\csflu\isms\models\map\StrategyMap;

/**
 * 
 *
 * @author britech
 */
class MapController extends Controller {

    private $mapService;

    public function __construct() {
        $this->checkAuthorization();
        $this->layout = 'column-2';
        $this->mapService = new StrategyMapService();
    }

    public function index() {
        $this->title = ApplicationConstants::APP_NAME . ' - Strategy Map Directory';
        $this->render('map/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => 'active'),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Create a Strategy Map' => array('map/create')))),
            'notif' => isset($_SESSION['notif']) ? $_SESSION['notif'] : ""
        ));
        if (isset($_SESSION['notif'])) {
            unset($_SESSION['notif']);
        }
    }

    public function renderStrategyMapTable() {
        $maps = $this->mapService->listStrategyMaps();

        $data = array();
        foreach ($maps as $map) {
            array_push($data, array(
                'name' => ApplicationUtils::generateLink(array('map/view', 'id' => $map->id), $map->name) . '<br/><i>' . $map->visionStatement . '</i>',
                'type' => StrategyMap::getStrategyTypes()[$map->strategyType],
                'status' => StrategyMap::getEnvironmentStatusTypes()[$map->strategyEnvironmentStatus]));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function create() {
        $this->layout = 'column-1';
        $this->title = ApplicationConstants::APP_NAME . ' - Create a Strategy Map';
        $this->render('map/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Create a Strategy Map' => 'active'),
            'validation' => isset($_SESSION['validation']) ? $_SESSION['validation'] : ""
        ));
        if (isset($_SESSION['validation'])) {
            unset($_SESSION['validation']);
        }
    }

    public function insert() {
        if (count(filter_input_array(INPUT_POST)) == 0) {
            throw new ValidationException('Another parameter is needed to process this request');
        }

        $strategyMapData = filter_input_array(INPUT_POST)['StrategyMap'];
        $strategyMap = new StrategyMap();
        $strategyMap->bindValuesUsingArray(array('strategymap' => $strategyMapData));
        $strategyMap->validationMode = Model::VALIDATION_MODE_INITIAL;
        if (!$strategyMap->validate()) {
            $_SESSION['validation'] = $strategyMap->validationMessages;
            $this->redirect(array('map/create'));
        } else {
            $id = $this->mapService->insert($strategyMap);
            $_SESSION['notif'] = array('class' => 'success', 'message' => 'New Strategy Map created. To finish the creation click '
                . ApplicationUtils::generateLink(array('map/complete', 'id' => $id), 'here.'));
            $this->redirect(array('map/index'));
        }
    }

}
