<?php

namespace org\csflu\isms\core;

spl_autoload_register(array(__NAMESPACE__."\\ApplicationLoader", 'loadClass'));

/**
 * Description of ApplicationAutoLoader
 *
 * @author britech
 */
class ApplicationLoader {

    private static $classes = array(
        
        // core packages
        'org\csflu\isms\core\Controller' => '/core/Controller.php',
        'org\csflu\isms\core\ApplicationConstants' => '/core/ApplicationConstants.php',
        'org\csflu\isms\core\Model' => '/core/Model.php',
        'org\csflu\isms\core\ConnectionManager' => '/core/ConnectionManager.php',
        'org\csflu\isms\core\ApplicationEnvironment' => '/core/ApplicationEnvironment.php',
        'org\csflu\isms\core\DatabaseConnectionManager' => '/core/DatabaseConnectionManager.php',
        
        // utility packages
        'org\csflu\isms\util\Component' => '/utils/Component.php',
        'org\csflu\isms\util\FormGenerator' => '/utils/FormGenerator.php',
        'org\csflu\isms\util\ModelFormGenerator' => '/utils/ModelFormGenerator.php',
        'org\csflu\isms\util\ApplicationUtils' => '/utils/ApplicationUtils.php',
        
        // controller support packages
        'org\csflu\isms\controllers\support\ModelLoaderUtil' => '/controllers/support/ModelLoaderUtil.php',
        'org\csflu\isms\controllers\support\CommitmentModuleSupport' => '/controllers/support/CommitmentModuleSupport.php',
        'org\csflu\isms\controllers\support\WigSessionControllerSupport' => '/controllers/support/WigSessionControllerSupport.php',
        'org\csflu\isms\controllers\support\UnitBreakthroughControllerSupport' => '/controllers/support/UnitBreakthroughControllerSupport.php',
        'org\csflu\isms\controllers\support\ActivityControllerSupport' => '/controllers/support/ActivityControllerSupport.php',
        'org\csflu\isms\controllers\support\ScorecardControllerSupport' => '/controllers/support/ScorecardControllerSupport.php',
        
        // logging extension package
        'Logger' => '/ext/log4php/Logger.php',
        
        // exception packages
        'org\csflu\isms\exceptions\DataAccessException' => '/exception/DataAccessException.php',
        'org\csflu\isms\exceptions\ServiceException' => '/exception/ServiceException.php',
        'org\csflu\isms\exceptions\ControllerException' => '/exception/ControllerException.php',
        'org\csflu\isms\exceptions\ModelException' => '/exception/ModelException.php',
        'org\csflu\isms\exceptions\ApplicationException' => '/exception/ApplicationException.php',
        
        // model packages
        'org\csflu\isms\models\commons\Department' => '/models/commons/Department.php',
        'org\csflu\isms\models\commons\Position' => '/models/commons/Position.php',
        'org\csflu\isms\models\commons\UnitOfMeasure' => '/models/commons/UnitOfMeasure.php',
        'org\csflu\isms\models\commons\RevisionHistory' => '/models/commons/RevisionHistory.php',

        'org\csflu\isms\models\uam\Employee' => '/models/uam/Employee.php',
        'org\csflu\isms\models\uam\UserAccount' => '/models/uam/UserAccount.php',
        'org\csflu\isms\models\uam\LoginAccount' => '/models/uam/LoginAccount.php',
        'org\csflu\isms\models\uam\SecurityRole' => '/models/uam/SecurityRole.php',
        'org\csflu\isms\models\uam\AllowableAction' => '/models/uam/AllowableAction.php',
        'org\csflu\isms\models\uam\ModuleAction' => '/models/uam/ModuleAction.php',

        'org\csflu\isms\models\indicator\Indicator' => '/models/indicator/Indicator.php',
        'org\csflu\isms\models\indicator\Baseline' => '/models/indicator/Baseline.php',
        'org\csflu\isms\models\indicator\MeasureProfile' => '/models/indicator/MeasureProfile.php',
        'org\csflu\isms\models\indicator\MeasureProfileMovement' => '/models/indicator/MeasureProfileMovement.php',
        'org\csflu\isms\models\indicator\MeasureProfileMovementLog' => '/models/indicator/MeasureProfileMovementLog.php',
        'org\csflu\isms\models\indicator\LeadOffice' => '/models/indicator/LeadOffice.php',
        'org\csflu\isms\models\indicator\Target' => '/models/indicator/Target.php',

        'org\csflu\isms\models\map\StrategyMap' => '/models/map/StrategyMap.php',
        'org\csflu\isms\models\map\Perspective' => '/models/map/Perspective.php',
        'org\csflu\isms\models\map\Theme' => '/models/map/Theme.php',
        'org\csflu\isms\models\map\Objective' => '/models/map/Objective.php',

        'org\csflu\isms\models\initiative\Initiative' => '/models/initiative/Initiative.php',
        'org\csflu\isms\models\initiative\ImplementingOffice' => '/models/initiative/ImplementingOffice.php',
        'org\csflu\isms\models\initiative\Phase' => '/models/initiative/Phase.php',
        'org\csflu\isms\models\initiative\Component' => '/models/initiative/Component.php',
        'org\csflu\isms\models\initiative\Activity' => '/models/initiative/Activity.php',
        'org\csflu\isms\models\initiative\ActivityMovement' => '/models/initiative/ActivityMovement.php',

        'org\csflu\isms\models\ubt\UnitBreakthrough' => '/models/ubt/UnitBreakthrough.php',
        'org\csflu\isms\models\ubt\LeadMeasure' => '/models/ubt/LeadMeasure.php',
        'org\csflu\isms\models\ubt\WigSession' => '/models/ubt/WigSession.php',
        'org\csflu\isms\models\ubt\WigMeeting' => '/models/ubt/WigMeeting.php',
        'org\csflu\isms\models\ubt\UnitBreakthroughMovement' => '/models/ubt/UnitBreakthroughMovement.php',
        'org\csflu\isms\models\ubt\Commitment' => '/models/ubt/Commitment.php',
        'org\csflu\isms\models\ubt\CommitmentMovement' => '/models/ubt/CommitmentMovement.php',

        'org\csflu\isms\models\reportsIpReportInput' => '/models/reports/IpReportInput.php',
        'org\csflu\isms\models\reportsIpReportOutput' => '/models/reports/IpReportOutput.php',
        
        // data access interfaces
        'org\csflu\isms\dao\commons\DepartmentDao' => '/dao/commons/DepartmentDao.php',
        'org\csflu\isms\dao\commons\PositionDao' => '/dao/commons/PositionDao.php',
        'org\csflu\isms\dao\commons\UnitOfMeasureDao' => '/dao/commons/UnitOfMeasureDao.php',
        'org\csflu\isms\dao\commons\RevisionHistoryLoggingDao' => '/dao/commons/RevisionHistoryLoggingDao.php',
        
        'org\csflu\isms\dao\uam\UserManagementDao' => '/dao/uam/UserManagementDao.php',
        'org\csflu\isms\dao\uam\SecurityRoleDao' => '/dao/uam/SecurityRoleDao.php',
        
        'org\csflu\isms\dao\indicator\IndicatorDao' => '/dao/indicator/IndicatorDao.php',
        'org\csflu\isms\dao\indicator\BaselineDao' => '/dao/indicator/BaselineDao.php',
        'org\csflu\isms\dao\indicator\MeasureProfileDao' => '/dao/indicator/MeasureProfileDao.php',
        'org\csflu\isms\dao\indicator\MeasureProfileMovementDao' => '/dao/indicator/MeasureProfileMovementDao.php',
        
        'org\csflu\isms\dao\map\StrategyMapDao' => '/dao/map/StrategyMapDao.php',
        'org\csflu\isms\dao\map\PerspectiveDao' => '/dao/map/PerspectiveDao.php',
        'org\csflu\isms\dao\map\ObjectiveDao' => '/dao/map/ObjectiveDao.php',
        
        'org\csflu\isms\dao\initiative\InitiativeDao' => '/dao/initiative/InitiativeDao.php',
        'org\csflu\isms\dao\initiative\PhaseDao' => '/dao/initiative/PhaseDao.php',
        'org\csflu\isms\dao\initiative\ComponentDao' => '/dao/initiative/ComponentDao.php',
        'org\csflu\isms\dao\initiative\ActivityDao' => '/dao/initiative/ActivityDao.php',
        'org\csflu\isms\dao\initiative\ActivityMovementDao' => '/dao/initiative/ActivityMovementDao.php',
        
        'org\csflu\isms\dao\ubt\UnitBreakthroughDao' => '/dao/ubt/UnitBreakthroughDao.php',
        'org\csflu\isms\dao\ubt\LeadMeasureDao' => '/dao/ubt/LeadMeasureDao.php',
        'org\csflu\isms\dao\ubt\WigSessionDao' => '/dao/ubt/WigSessionDao.php',
        'org\csflu\isms\dao\ubt\CommitmentCrudDao' => '/dao/ubt/CommitmentCrudDao.php',
        'org\csflu\isms\dao\ubt\CommitmentMovementDao' => '/dao/ubt/CommitmentMovementDao.php',
        'org\csflu\isms\dao\ubt\UnitBreakthroughMovementDao' => '/dao/ubt/UnitBreakthroughMovementDao.php',
        
        'org\csflu\isms\dao\reports\IpReportDao' => '/dao/reports/IpReportDao.php',

        // data access implementations
        'org\csflu\isms\dao\commons\DepartmentDaoSqlImpl' => '/dao/commons/DepartmentDaoSqlImpl.php',
        'org\csflu\isms\dao\commons\PositionDaoSqlImpl' => '/dao/commons/PositionDaoSqlImpl.php',
        'org\csflu\isms\dao\commons\UnitOfMeasureDaoSqlImpl' => '/dao/commons/UnitOfMeasureDaoSqlImpl.php',
        'org\csflu\isms\dao\commons\RevisionHistoryLoggingDaoSqlImpl' => '/dao/commons/RevisionHistoryLoggingDaoSqlImpl.php',
        
        'org\csflu\isms\dao\uam\UserManagementDaoSqlImpl' => '/dao/uam/UserManagementDaoSqlImpl.php',
        'org\csflu\isms\dao\uam\SecurityRoleDaoSqlImpl' => '/dao/uam/SecurityRoleDaoSqlImpl.php',
        
        'org\csflu\isms\dao\indicator\IndicatorDaoSqlImpl' => '/dao/indicator/IndicatorDaoSqlImpl.php',
        'org\csflu\isms\dao\indicator\BaselineDaoSqlImpl' => '/dao/indicator/BaselineDaoSqlImpl.php',
        'org\csflu\isms\dao\indicator\MeasureProfileDaoSqlImpl' => '/dao/indicator/MeasureProfileDaoSqlImpl.php',
        'org\csflu\isms\dao\indicator\MeasureProfileMovementDaoSqlImpl' => '/dao/indicator/MeasureProfileMovementDaoSqlImpl.php',
        
        'org\csflu\isms\dao\map\StrategyMapDaoSqlImpl' => '/dao/map/StrategyMapDaoSqlImpl.php',
        'org\csflu\isms\dao\map\PerspectiveDaoSqlImpl' => '/dao/map/PerspectiveDaoSqlImpl.php',
        'org\csflu\isms\dao\map\ObjectiveDaoSqlImpl' => '/dao/map/ObjectiveDaoSqlImpl.php',
        
        'org\csflu\isms\dao\initiative\InitiativeDaoSqlImpl' => '/dao/initiative/InitiativeDaoSqlImpl.php',
        'org\csflu\isms\dao\initiative\PhaseDaoSqlImpl' => '/dao/initiative/PhaseDaoSqlImpl.php',
        'org\csflu\isms\dao\initiative\ComponentDaoSqlImpl' => '/dao/initiative/ComponentDaoSqlImpl.php',
        'org\csflu\isms\dao\initiative\ActivityDaoSqlImpl' => '/dao/initiative/ActivityDaoSqlImpl.php',
        'org\csflu\isms\dao\initiative\ActivityMovementDaoSqlImpl' => '/dao/initiative/ActivityMovementDaoSqlImpl.php',
        
        'org\csflu\isms\dao\ubt\UnitBreakthroughDaoSqlImpl' => '/dao/ubt/UnitBreakthroughDaoSqlImpl.php',
        'org\csflu\isms\dao\ubt\LeadMeasureDaoSqlImpl' => '/dao/ubt/LeadMeasureDaoSqlImpl.php',
        'org\csflu\isms\dao\ubt\WigSessionDaoSqlmpl' => '/dao/ubt/WigSessionDaoSqlmpl.php',
        'org\csflu\isms\dao\ubt\CommitmentCrudDaoSqlImpl' => '/dao/ubt/CommitmentCrudDaoSqlImpl.php',
        'org\csflu\isms\dao\ubt\CommitmentMovementDaoSqlImpl' => '/dao/ubt/CommitmentMovementDaoSqlImpl.php',
        'org\csflu\isms\dao\ubt\UnitBreakthroughMovementDaoSqlImpl' => '/dao/ubt/UnitBreakthroughMovementDaoSqlImpl.php',
        
        'org\csflu\isms\dao\reports\IpReportDaoSqlImpl' => '/dao/reports/IpReportDaoSqlImpl.php',

        // service layer interfaces
        'org\csflu\isms\service\commons\DepartmentService' => '/services/commons/DepartmentService.php',
        'org\csflu\isms\service\commons\PositionService' => '/services/commons/PositionService.php',
        'org\csflu\isms\service\commons\UnitOfMeasureService' => '/services/commons/UnitOfMeasureService.php',
        'org\csflu\isms\service\commons\RevisionHistoryLoggingService' => '/services/commons/RevisionHistoryLoggingService.php',
        
        'org\csflu\isms\service\uam\UserManagementService' => '/services/uam/UserManagementService.php',
        'org\csflu\isms\service\uam\RbacService' => '/services/uam/RbacService.php',

        'org\csflu\isms\service\indicator\IndicatorManagementService' => '/services/indicator/IndicatorManagementService.php',
        'org\csflu\isms\service\indicator\ScorecardManagementService' => '/services/indicator/ScorecardManagementService.php',
        
        'org\csflu\isms\service\map\StrategyMapManagementService' => '/services/map/StrategyMapManagementService.php',
        
        'org\csflu\isms\service\initiative\InitiativeManagementService' => '/services/initiative/InitiativeManagementService.php',
        
        'org\csflu\isms\service\ubt\UnitBreakthroughManagementService' => '/services/ubt/UnitBreakthroughManagementService.php',
        'org\csflu\isms\service\ubt\CommitmentManagementService' => '/services/ubt/CommitmentManagementService.php',
       
        'org\csflu\isms\service\reports\IpReportService' => '/services/reports/IpReportService.php',
        
        'org\csflu\isms\service\alignment\StrategyAlignmentService' => '/services/alignment/StrategyAlignmentService.php',
        

        // service layer implementations
        'org\csflu\isms\service\commons\DepartmentServiceSimpleImpl' => '/services/commons/DepartmentServiceSimpleImpl.php',
        'org\csflu\isms\service\commons\PositionServiceSimpleImpl' => '/services/commons/PositionServiceSimpleImpl.php',
        'org\csflu\isms\service\commons\UnitOfMeasureSimpleImpl' => '/services/commons/UnitOfMeasureSimpleImpl.php',
        'org\csflu\isms\service\commons\RevisionHistoryLoggingServiceImpl' => '/services/commons/RevisionHistoryLoggingServiceImpl.php',
        
        'org\csflu\isms\service\uam\SimpleUserManagementServiceImpl' => '/services/uam/SimpleUserManagementServiceImpl.php',
        'org\csflu\isms\service\uam\RbacServiceImpl' => '/services/uam/RbacServiceImpl.php',
        
        'org\csflu\isms\service\indicator\IndicatorManagementServiceSimpleImpl' => '/services/indicator/IndicatorManagementServiceSimpleImpl.php',
        'org\csflu\isms\service\indicator\ScorecardManagementServiceSimpleImpl' => '/services/indicator/ScorecardManagementServiceSimpleImpl.php',
        
        'org\csflu\isms\service\map\StrategyMapManagementServiceSimpleImpl' => '/services/map/StrategyMapManagementServiceSimpleImpl.php',
        
        'org\csflu\isms\service\initiative\InitiativeManagementServiceSimpleImpl' => '/services/initiative/InitiativeManagementServiceSimpleImpl.php',
        
        'org\csflu\isms\service\ubt\UnitBreakthroughManagementServiceSimpleImpl' => '/services/ubt/UnitBreakthroughManagementServiceSimpleImpl.php',
        'org\csflu\isms\service\ubt\CommitmentManagementServiceSimpleImpl' => '/services/ubt/CommitmentManagementServiceSimpleImpl.php',
        
        'org\csflu\isms\service\reports\IpReportServiceImpl' => '/services/reports/IpReportServiceImpl.php',
        
        'org\csflu\isms\service\alignment\StrategyAlignmentServiceSimpleImpl' => '/services/alignment/StrategyAlignmentServiceSimpleImpl.php',
    );

    public static function loadClass($className) {
        if (isset(self::$classes) && array_key_exists($className, self::$classes)) {
            require_once dirname(__DIR__) . self::$classes[$className];
        }
    }

}
