<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;
use org\csflu\isms\exceptions\ControllerException;
use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\core\ApplicationConstants;
use org\csflu\isms\util\ApplicationUtils;
use org\csflu\isms\core\Model;
use org\csflu\isms\models\indicator\MeasureProfile;
use org\csflu\isms\models\map\Objective;
use org\csflu\isms\models\indicator\Indicator;
use org\csflu\isms\models\indicator\LeadOffice;
use org\csflu\isms\models\indicator\Target;
use org\csflu\isms\models\commons\Department;
use org\csflu\isms\models\uam\ModuleAction;
use org\csflu\isms\models\commons\RevisionHistory;
use org\csflu\isms\service\commons\DepartmentServiceSimpleImpl as DepartmentService;
use org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl as StrategyMapManagementService;
use org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl as IndicatorManagementService;
use org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl as ScorecardManagementService;

/**
 * Description of MeasureController
 *
 * @author britech
 */
class MeasureController extends Controller {

    private $logger;
    private $departmentService;
    private $mapService;
    private $indicatorService;
    private $scorecardService;

    public function __construct() {
        $this->checkAuthorization();
        $this->isRbacEnabled = true;
        $this->moduleCode = ModuleAction::MODULE_SCARD;
        $this->actionCode = "MPM";
        $this->departmentService = new DepartmentService();
        $this->mapService = new StrategyMapManagementService();
        $this->indicatorService = new IndicatorManagementService();
        $this->scorecardService = new ScorecardManagementService();
        $this->logger = \Logger::getLogger(__CLASS__);
    }

    public function index($map) {
        $this->actionCode = "MPV";
        $strategyMap = $this->loadMapModel($map);
        $this->layout = 'column-2';
        $this->title = ApplicationConstants::APP_NAME . ' - Measure Profiles';
        $this->render('measure-profile/index', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => 'active'),
            'sidebar' => array(
                'file' => 'measure-profile/_index-navi'),
            'map' => $strategyMap->id,
            'startingPeriod' => $strategyMap->startingPeriodDate->format('Y-m-d'),
            'endingPeriod' => $strategyMap->endingPeriodDate->format('Y-m-d')
        ));
    }

    public function create($map) {
        if (!isset($map) || empty($map)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }
        $strategyMap = $this->loadMapModel($map);
        $strategyMap->startingPeriodDate = $strategyMap->startingPeriodDate->format('Y-m-d');
        $strategyMap->endingPeriodDate = $strategyMap->endingPeriodDate->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Create Measure Profile';
        $this->render('measure-profile/create', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Create Measure Profile' => 'active'
            ),
            'model' => new MeasureProfile(),
            'objectiveModel' => new Objective(),
            'indicatorModel' => new Indicator(),
            'mapModel' => $strategyMap,
            'frequencyTypes' => MeasureProfile::getFrequencyTypes(),
            'measureTypes' => MeasureProfile::getMeasureTypes(),
            'statusTypes' => MeasureProfile::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function insert() {
        $this->validatePostData(array('MeasureProfile', 'Objective', 'Indicator', 'StrategyMap'));

        $measureProfileData = $this->getFormData('MeasureProfile');
        $objectiveData = $this->getFormData('Objective');
        $indicatorData = $this->getFormData('Indicator');
        $strategyMapData = $this->getFormData('StrategyMap');

        $strategyMap = $this->loadMapModel($strategyMapData['id']);

        $measureProfile = new MeasureProfile();
        $measureProfile->bindValuesUsingArray(array('measureprofile' => $measureProfileData), $measureProfile);
        $measureProfile->objective = $this->mapService->getObjective($objectiveData['id']);
        $measureProfile->indicator = $this->indicatorService->retrieveIndicator($indicatorData['id']);

        if (!$measureProfile->validate()) {
            $this->setSessionData('validation', $measureProfile->validationMessages);
            $this->redirect(array('measure/create', 'map' => $strategyMap->id));
        }

        try {
            $id = $this->scorecardService->insertMeasureProfile($measureProfile, $strategyMap);
            $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $id, $measureProfile);
            $this->redirect(array('measure/view', 'id' => $id));
        } catch (ServiceException $ex) {
            $this->setSessionData('validation', array($ex->getMessage()));
            $this->redirect(array('measure/create', 'map' => $strategyMap->id));
        }
    }

    public function update($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('MeasureProfile', 'Objective', 'Indicator'));
            $this->processProfileUpdate();
        }

        $measureProfile = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $measureProfile->timelineStart = $measureProfile->timelineStart->format('Y-m-d');
        $measureProfile->timelineEnd = $measureProfile->timelineEnd->format('Y-m-d');
        $this->title = ApplicationConstants::APP_NAME . ' - Update Measure Profile';
        $this->render('measure-profile/update', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Update Profile' => 'active'
            ),
            'model' => $measureProfile,
            'objectiveModel' => $measureProfile->objective,
            'indicatorModel' => $measureProfile->indicator,
            'mapModel' => $strategyMap,
            'measureTypes' => MeasureProfile::getMeasureTypes(),
            'frequencyTypes' => MeasureProfile::getFrequencyTypes(),
            'statusTypes' => MeasureProfile::getEnvironmentStatusTypes(),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function processProfileUpdate() {
        $measureProfileData = $this->getFormData('MeasureProfile');
        $objectiveData = $this->getFormData('Objective');
        $indicatorData = $this->getFormData('Indicator');

        $oldMeasureProfile = clone $this->loadModel($measureProfileData['id']);

        $measureProfile = $this->loadModel($measureProfileData['id']);
        $measureProfile->bindValuesUsingArray(array('measureprofile' => $measureProfileData));
        $measureProfile->objective = $this->mapService->getObjective($objectiveData['id']);
        $measureProfile->indicator = $this->indicatorService->retrieveIndicator($indicatorData['id']);

        if (!$measureProfile->validate()) {
            $this->setSessionData('validation', $measureProfile->validationMessages);
            $this->redirect(array('measure/update', 'id' => $measureProfile->id));
        }

        if ($measureProfile->computePropertyChanges($oldMeasureProfile) > 0) {
            try {
                $this->scorecardService->updateMeasureProfile($measureProfile);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SCARD, $measureProfile->id, $measureProfile, $oldMeasureProfile);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Measure Profile updated'));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
                $this->redirect(array('measure/update', 'id' => $measureProfile->id));
            }
        }
        $this->redirect(array('measure/view', 'id' => $measureProfile->id));
    }

    public function validateInput() {
        try {
            $this->validatePostData(array('MeasureProfile', 'Objective', 'Indicator'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $measureProfileData = $this->getFormData('MeasureProfile');
        $objectiveData = $this->getFormData('Objective');
        $indicatorData = $this->getFormData('Indicator');

        $measureProfile = new MeasureProfile();
        $measureProfile->bindValuesUsingArray(array(
            'measureprofile' => $measureProfileData,
            'objective' => $objectiveData,
            'indicator' => $indicatorData));
        $this->remoteValidateModel($measureProfile);
    }

    public function view($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }
        $this->actionCode = "MPV";
        $measureProfile = $this->loadModel($id);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $this->layout = 'column-2';
        $this->title = ApplicationConstants::APP_NAME . ' - Profile';
        $this->render('measure-profile/view', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => 'active'
            ),
            'sidebar' => array(
                'data' => array(
                    'header' => 'Actions',
                    'links' => array(
                        'Update Profile' => array('measure/update', 'id' => $measureProfile->id),
                        'Manage Lead Offices' => array('measure/manageOffices', 'profile' => $measureProfile->id),
                        'Manage Targets' => array('measure/manageTargets', 'profile' => $measureProfile->id),
                        'Generate Measure Profile' => array('report/measureProfile', 'id' => $measureProfile->id)
                    )
                )
            ),
            'model' => $measureProfile,
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('notif');
    }

    public function manageOffices($profile) {
        if (!isset($profile) || empty($profile)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $measureProfile = $this->loadModel($profile);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Offices';
        $this->render('measure-profile/lead-office', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Manage Lead Offices' => 'active'
            ),
            'model' => new LeadOffice(),
            'departmentModel' => new Department,
            'measureProfileModel' => $measureProfile,
            'designationTypes' => LeadOffice::getDesignationOptions(),
            'validation' => $this->getSessionData('validation'),
            'notif' => $this->getSessionData('notif')
        ));
        $this->unsetSessionData('validation');
        $this->unsetSessionData('notif');
    }

    public function validateLeadOfficeInput() {
        try {
            $this->validatePostData(array('LeadOffice', 'Department', 'mode'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $leadOfficeData = $this->getFormData('LeadOffice');
        $departmentData = $this->getFormData('Department');
        $mode = $this->getFormData('mode');

        $leadOffice = new LeadOffice();
        $leadOffice->validationMode = $mode;
        $leadOffice->bindValuesUsingArray(array(
            'leadoffice' => $leadOfficeData,
            'department' => $departmentData
        ));

        $this->remoteValidateModel($leadOffice);
    }

    public function listLeadOffices() {
        $this->validatePostData(array('profile'));

        $profile = $this->getFormData('profile');
        $measureProfile = $this->scorecardService->getMeasureProfile($profile);

        $data = array();
        foreach ($measureProfile->leadOffices as $leadOffice) {
            $designations = explode($leadOffice->arrayDelimiter, $leadOffice->designation);
            foreach ($designations as $designation) {
                array_push($data, array(
                    'id' => $leadOffice->id,
                    'department' => $leadOffice->department->name,
                    'designation' => LeadOffice::getDesignationOptions()[$designation],
                    'actions' => ApplicationUtils::generateLink(array('measure/updateLeadOffice', 'id' => $leadOffice->id), 'Update')
                    . '&nbsp;|&nbsp;' .
                    ApplicationUtils::generateLink('#', 'Delete', array('id' => "remove-{$leadOffice->id}"))
                ));
            }
        }

        $this->renderAjaxJsonResponse($data);
    }

    public function insertLeadOffice() {
        $this->validatePostData(array('LeadOffice', 'Department', 'MeasureProfile'));
        $leadOfficeData = $this->getFormData('LeadOffice');
        $departmentData = $this->getFormData('Department');
        $measureProfileData = $this->getFormData('MeasureProfile');

        $leadOffices = array();
        foreach (explode("/", $departmentData['id']) as $department) {
            $leadOffice = new LeadOffice();
            $leadOffice->bindValuesUsingArray(array(
                'leadoffice' => $leadOfficeData
            ));
            $leadOffice->department = $this->departmentService->getDepartmentDetail(array('id' => $department));
            array_push($leadOffices, $leadOffice);
        }

        $measureProfile = $this->loadModel($measureProfileData['id']);
        $measureProfile->leadOffices = $leadOffices;

        if (count($measureProfile) > 0) {
            try {
                $this->scorecardService->insertLeadOffices($measureProfile);
                foreach ($measureProfile->leadOffices as $leadOffice) {
                    $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $measureProfile->id, $leadOffice);
                }
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Lead Office/s added'));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', array('Lead Offices must be defined'));
        }
        $this->redirect(array('measure/manageOffices', 'profile' => $measureProfile->id));
    }

    public function updateLeadOffice($id = null) {
        if (is_null($id)) {
            $this->validatePostData(array('LeadOffice', 'Department'));
            $this->processLeadOfficeUpdate();
        }

        $temp = new LeadOffice();
        $temp->id = $id;

        $measureProfile = $this->loadModel(null, $temp);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $leadOffice = $this->scorecardService->getLeadOffice($measureProfile, $id);
        $leadOffice->validationMode = Model::VALIDATION_MODE_UPDATE;

        $this->title = ApplicationConstants::APP_NAME . ' - Manage Lead Offices';
        $this->render('measure-profile/lead-office', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Manage Lead Offices' => array('measure/manageOffices', 'profile' => $measureProfile->id),
                'Update Lead Office' => 'active'
            ),
            'model' => $leadOffice,
            'departmentModel' => $leadOffice->department,
            'measureProfileModel' => $measureProfile,
            'designationTypes' => LeadOffice::getDesignationOptions(),
            'validation' => $this->getSessionData('validation'),
        ));
        $this->unsetSessionData('validation');
    }

    private function processLeadOfficeUpdate() {
        $leadOfficeData = $this->getFormData('LeadOffice');
        $departmentData = $this->getFormData('Department');

        $leadOffice = new LeadOffice();
        $leadOffice->bindValuesUsingArray(array(
            'leadoffice' => $leadOfficeData,
            'department' => $departmentData));

        $measureProfile = $this->loadModel(null, $leadOffice);
        $oldLeadOffice = clone $this->scorecardService->getLeadOffice($measureProfile, $leadOffice->id);

        if ($leadOffice->computePropertyChanges($oldLeadOffice) > 0) {
            $this->scorecardService->updateLeadOffice($leadOffice);
            $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SCARD, $measureProfile->id, $leadOffice, $oldLeadOffice);
            $this->setSessionData('notif', array('class' => 'info', 'message' => 'Lead Office updated'));
        }

        $this->redirect(array('measure/manageOffices', 'profile' => $measureProfile->id));
    }

    public function deleteLeadOffice() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $tempLeadOffice = new LeadOffice();
        $tempLeadOffice->id = $id;

        $measureProfile = $this->scorecardService->getMeasureProfile(null, $tempLeadOffice);
        if (is_null($measureProfile->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Measure Profile not found'));
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
        }

        $leadOffice = $this->scorecardService->getLeadOffice($measureProfile, $id);
        if (is_null($leadOffice->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Lead Office not found'));
        }
        $this->scorecardService->deleteLeadOffice($leadOffice->id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SCARD, $measureProfile->id, $leadOffice);
        $this->setSessionData('notif', array('class' => '', 'message' => 'Lead Office deleted'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('measure/manageOffices', 'profile' => $measureProfile->id))));
    }

    public function manageTargets($profile) {
        if (!isset($profile) || empty($profile)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $measureProfile = $this->loadModel($profile);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $this->title = ApplicationConstants::APP_NAME . ' - Manage Targets';
        $this->render('measure-profile/target', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Manage Targets' => 'active'
            ),
            'model' => new Target(),
            'profileModel' => $measureProfile,
            'uom' => $measureProfile->indicator->uom,
            'baselineReference' => $measureProfile->indicator->baselineData[count($measureProfile->indicator->baselineData) - 1],
            'notif' => $this->getSessionData('notif'),
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('notif');
        $this->unsetSessionData('validation');
    }

    public function validateTargetInput() {
        try {
            $this->validatePostData(array('Target'));
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage(), $ex);
            $this->renderAjaxJsonResponse(array('respCode' => '70'));
        }

        $targetData = $this->getFormData('Target');
        $target = new Target();
        $target->bindValuesUsingArray(array('target' => $targetData), $target);
        $this->remoteValidateModel($target);
    }

    public function insertTargetData() {
        $this->validatePostData(array('Target', 'MeasureProfile'));

        $profileData = $this->getFormData('MeasureProfile');
        $targetData = $this->getFormData('Target');

        $measureProfile = $this->loadModel($profileData['id']);

        $target = new Target();
        $target->bindValuesUsingArray(array('target' => $targetData), $target);
        if ($target->validate()) {
            $measureProfile->targets = array($target);
            try {
                $this->scorecardService->insertTargets($measureProfile);
                $this->logRevision(RevisionHistory::TYPE_INSERT, ModuleAction::MODULE_SCARD, $measureProfile->id, $target);
                $this->setSessionData('notif', array('class' => 'success', 'message' => 'Target data successfully added'));
            } catch (ServiceException $ex) {
                $this->logger->error($ex->getMessage(), $ex);
                $this->setSessionData('validation', array($ex->getMessage()));
            }
        } else {
            $this->setSessionData('validation', $target->validationMessages);
        }
        $this->redirect(array('measure/manageTargets', 'profile' => $measureProfile->id));
    }

    public function listTargets() {
        $this->validatePostData(array('profile'));
        $profile = $this->getFormData('profile');

        $measureProfile = $this->loadModel($profile);
        $uom = strlen($measureProfile->indicator->uom->symbol) == 0 ? $measureProfile->indicator->uom->description : $measureProfile->indicator->uom->symbol;

        $data = array();
        foreach ($measureProfile->targets as $target) {
            $figure = is_numeric($target->value) ? number_format($target->value, 2) : $target->value;
            array_push($data, array(
                'id' => $target->id,
                'group' => $target->dataGroup,
                'year' => $target->coveredYear,
                'value' => $figure . '&nbsp;' . strval($uom), 'action' => ApplicationUtils::generateLink('#', 'View', array('id' => "view-{$target->id}"))
                . '&nbsp;|&nbsp;' .
                ApplicationUtils::generateLink(array('measure/updateTarget', 'id' => $target->id), 'Update')
                . '&nbsp;|&nbsp;' .
                ApplicationUtils::generateLink("#", 'Delete', array('id' => "remove-{$target->id}"))
            ));
        }
        $this->renderAjaxJsonResponse($data);
    }

    public function getTarget() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $targetTemp = new Target();
        $targetTemp->id = $id;

        $measureProfile = $this->loadModel(null, null, $targetTemp);
        $target = $this->scorecardService->getTarget($measureProfile, $id);

        $uom = strlen($measureProfile->indicator->uom->symbol) == 0 ? $measureProfile->indicator->uom->description : $measureProfile->indicator->uom->symbol;
        $dataGroupContent = strlen($target->dataGroup) == 0 ? "" : "Data Group: {$target->dataGroup}";
        $notesContent = strlen($target->notes) == 0 ? "" : $target->notes;
        $notes = nl2br("{$dataGroupContent}\n{$notesContent}");

        $this->renderAjaxJsonResponse(array(
            'coveredYear' => $target->coveredYear,
            'figureValue' => $target->value . '&nbsp;' . strval($uom),
            'notes' => $notes
        ));
    }

    public function updateTarget($id) {
        if (!isset($id) || empty($id)) {
            throw new ControllerException("Another parameter is needed to process this request");
        }

        $tempTarget = new Target();
        $tempTarget->id = $id;
        $measureProfile = $this->loadModel(null, null, $tempTarget);
        $strategyMap = $this->loadMapModel(null, $measureProfile->objective);
        $target = $this->scorecardService->getTarget($measureProfile, $id);
        $target->validationMode = Model::VALIDATION_MODE_UPDATE;
        $this->title = ApplicationConstants::APP_NAME . ' - Update Target';
        $this->render('measure-profile/target', array(
            'breadcrumb' => array(
                'Home' => array('site/index'),
                'Strategy Map Directory' => array('map/index'),
                'Strategy Map' => array('map/view', 'id' => $strategyMap->id),
                'Manage Measure Profiles' => array('measure/index', 'map' => $strategyMap->id),
                'Profile' => array('measure/view', 'id' => $measureProfile->id),
                'Manage Targets' => array('measure/manageTargets', 'profile' => $measureProfile->id),
                'Update Target Data' => 'active'
            ),
            'model' => $target,
            'profileModel' => $measureProfile,
            'uom' => $measureProfile->indicator->uom,
            'baselineReference' => $measureProfile->indicator->baselineData[count($measureProfile->indicator->baselineData) - 1],
            'validation' => $this->getSessionData('validation')
        ));
        $this->unsetSessionData('validation');
    }

    public function updateTargetData() {
        $this->validatePostData(array('Target', 'MeasureProfile'));

        $measureProfileData = $this->getFormData('MeasureProfile');
        $targetData = $this->getFormData('Target');

        $measureProfile = $this->loadModel($measureProfileData['id']);

        $target = new Target();
        $target->bindValuesUsingArray(array('target' => $targetData), $target);

        if ($target->validate()) {
            $oldTarget = clone $this->scorecardService->getTarget($measureProfile, $target->id);

            if ($target->computePropertyChanges($oldTarget) > 0) {
                $this->scorecardService->updateTarget($target);
                $this->logRevision(RevisionHistory::TYPE_UPDATE, ModuleAction::MODULE_SCARD, $measureProfile->id, $target, $oldTarget);
                $this->setSessionData('notif', array('class' => 'info', 'message' => 'Target data updated'));
            }
            $this->redirect(array('measure/manageTargets', 'profile' => $measureProfile->id));
        } else {
            $this->setSessionData('validation', $target->validationMessages);
            $this->redirect(array('measure/updateTarget', 'id' => $target->id));
        }
    }

    public function deleteTargetData() {
        $this->validatePostData(array('id'));
        $id = $this->getFormData('id');

        $tempTarget = new Target();
        $tempTarget->id = $id;

        $measureProfile = $this->scorecardService->getMeasureProfile(null, null, $tempTarget);
        if (is_null($measureProfile->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Measure Profile not found'));
            $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('map/index'))));
        }
        $target = clone $this->scorecardService->getTarget($measureProfile, $id);
        $this->scorecardService->deleteTarget($target->id);
        $this->logRevision(RevisionHistory::TYPE_DELETE, ModuleAction::MODULE_SCARD, $measureProfile->id, $target);
        $this->setSessionData('notif', array('class' => '', 'message' => 'Target data deleted'));
        $this->renderAjaxJsonResponse(array('url' => ApplicationUtils::resolveUrl(array('measure/manageTargets', 'profile' => $measureProfile->id))));
    }

    private function loadModel($id = null, LeadOffice $leadOffice = null, Target $target = null) {
        if (!is_null($id)) {
            $measureProfile = $this->scorecardService->getMeasureProfile($id);
        } elseif (!is_null($leadOffice)) {
            $measureProfile = $this->scorecardService->getMeasureProfile(null, $leadOffice);
        } elseif (!is_null($target)) {
            $measureProfile = $this->scorecardService->getMeasureProfile(null, null, $target);
        }

        if (is_null($measureProfile->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Measure Profile not found'));
            $this->redirect(array('map/index'));
        }
        return $measureProfile;
    }

    private function loadMapModel($id = null, Objective $objective = null) {
        $map = $this->mapService->getStrategyMap($id, null, $objective);
        if (is_null($map->id)) {
            $this->setSessionData('notif', array('class' => '', 'message' => 'Strategy Map not found'));
            $this->redirect(array('map/index'));
        }
        return $map;
    }

}
