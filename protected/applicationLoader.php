<?php

namespace org\csflu\isms\core;

#core components
require_once 'core/Application.php';
require_once 'core/Controller.php';
require_once 'core/ApplicationConstants.php';
require_once 'core/Model.php';
require_once 'core/ConnectionManager.php';

#core util components
require_once 'utils/Component.php';
require_once 'utils/FormGenerator.php';
require_once 'utils/ModelFormGenerator.php';
require_once 'utils/ApplicationUtils.php';

#module support components
require_once 'controllers/support/ModelLoaderUtil.php';
require_once 'controllers/support/CommitmentModuleSupport.php';
require_once 'controllers/support/WigSessionControllerSupport.php';
require_once 'controllers/support/UnitBreakthroughControllerSupport.php';
require_once 'controllers/support/ActivityControllerSupport.php';

#logging extension
require_once 'ext/log4php/Logger.php';
\Logger::configure("protected/logs/logback.xml");

#report generation extension
#Due to enormous size, It is recommended that the extension
#be located outside of the application.
#Upon deployment, please update the location
#of the mpdf60 directory
require_once '/home/britech/mpdf60/mpdf.php';

#custom exceptions
require_once 'exception/DataAccessException.php';
require_once 'exception/ServiceException.php';
require_once 'exception/ControllerException.php';
require_once 'exception/ModelException.php';
require_once 'exception/ApplicationException.php';

#models
require_once 'models/commons/Department.php';
require_once 'models/commons/Position.php';
require_once 'models/commons/UnitOfMeasure.php';
require_once 'models/commons/RevisionHistory.php';

require_once 'models/uam/Login.php';
require_once 'models/uam/Employee.php';
require_once 'models/uam/UserAccount.php';
require_once 'models/uam/LoginAccount.php';
require_once 'models/uam/SecurityRole.php';
require_once 'models/uam/AllowableAction.php';
require_once 'models/uam/ModuleAction.php';

require_once 'models/indicator/Indicator.php';
require_once 'models/indicator/Baseline.php';
require_once 'models/indicator/MeasureProfile.php';
require_once 'models/indicator/LeadOffice.php';
require_once 'models/indicator/Target.php';

require_once 'models/map/StrategyMap.php';
require_once 'models/map/Perspective.php';
require_once 'models/map/Theme.php';
require_once 'models/map/Objective.php';

require_once 'models/initiative/Initiative.php';
require_once 'models/initiative/ImplementingOffice.php';
require_once 'models/initiative/Phase.php';
require_once 'models/initiative/Component.php';
require_once 'models/initiative/Activity.php';
require_once 'models/initiative/ActivityMovement.php';

require_once 'models/ubt/UnitBreakthrough.php';
require_once 'models/ubt/LeadMeasure.php';
require_once 'models/ubt/WigSession.php';
require_once 'models/ubt/WigMeeting.php';
require_once 'models/ubt/UnitBreakthroughMovement.php';
require_once 'models/ubt/Commitment.php';
require_once 'models/ubt/CommitmentMovement.php';

require_once 'models/reports/IpReportInput.php';
require_once 'models/reports/IpReportOutput.php';

#dao-interfaces
require_once 'dao/commons/DepartmentDao.php';
require_once 'dao/commons/PositionDao.php';
require_once 'dao/commons/UnitOfMeasureDao.php';
require_once 'dao/commons/RevisionHistoryLoggingDao.php';
require_once 'dao/uam/UserManagementDao.php';
require_once 'dao/uam/SecurityRoleDao.php';
require_once 'dao/indicator/IndicatorDao.php';
require_once 'dao/indicator/BaselineDao.php';
require_once 'dao/indicator/MeasureProfileDao.php';
require_once 'dao/map/StrategyMapDao.php';
require_once 'dao/map/PerspectiveDao.php';
require_once 'dao/map/ObjectiveDao.php';
require_once 'dao/initiative/InitiativeDao.php';
require_once 'dao/initiative/PhaseDao.php';
require_once 'dao/initiative/ComponentDao.php';
require_once 'dao/initiative/ActivityDao.php';
require_once 'dao/initiative/ActivityMovementDao.php';
require_once 'dao/ubt/UnitBreakthroughDao.php';
require_once 'dao/ubt/LeadMeasureDao.php';
require_once 'dao/ubt/WigSessionDao.php';
require_once 'dao/ubt/CommitmentCrudDao.php';
require_once 'dao/ubt/CommitmentMovementDao.php';
require_once 'dao/ubt/UnitBreakthroughMovementDao.php';
require_once 'dao/reports/IpReportDao.php';

#dao-implementations
require_once 'dao/commons/DepartmentDaoSqlImpl.php';
require_once 'dao/commons/PositionDaoSqlImpl.php';
require_once 'dao/commons/UnitOfMeasureDaoSqlImpl.php';
require_once 'dao/commons/RevisionHistoryLoggingDaoSqlImpl.php';
require_once 'dao/uam/UserManagementDaoSqlImpl.php';
require_once 'dao/uam/SecurityRoleDaoSqlImpl.php';
require_once 'dao/indicator/IndicatorDaoSqlImpl.php';
require_once 'dao/indicator/BaselineDaoSqlImpl.php';
require_once 'dao/indicator/MeasureProfileDaoSqlImpl.php';
require_once 'dao/map/StrategyMapDaoSqlImpl.php';
require_once 'dao/map/PerspectiveDaoSqlImpl.php';
require_once 'dao/map/ObjectiveDaoSqlImpl.php';
require_once 'dao/initiative/InitiativeDaoSqlImpl.php';
require_once 'dao/initiative/PhaseDaoSqlImpl.php';
require_once 'dao/initiative/ComponentDaoSqlImpl.php';
require_once 'dao/initiative/ActivityDaoSqlImpl.php';
require_once 'dao/initiative/ActivityMovementDaoSqlImpl.php';
require_once 'dao/ubt/UnitBreakthroughDaoSqlImpl.php';
require_once 'dao/ubt/LeadMeasureDaoSqlImpl.php';
require_once 'dao/ubt/WigSessionDaoSqlmpl.php';
require_once 'dao/ubt/CommitmentCrudDaoSqlImpl.php';
require_once 'dao/ubt/CommitmentMovementDaoSqlImpl.php';
require_once 'dao/ubt/UnitBreakthroughMovementDaoSqlImpl.php';
require_once 'dao/reports/IpReportDaoSqlImpl.php';

#service-interfaces
require_once 'services/commons/DepartmentService.php';
require_once 'services/commons/PositionService.php';
require_once 'services/commons/UnitOfMeasureService.php';
require_once 'services/commons/RevisionHistoryLoggingService.php';
require_once 'services/uam/UserManagementService.php';
require_once 'services/indicator/IndicatorManagementService.php';
require_once 'services/indicator/ScorecardManagementService.php';
require_once 'services/map/StrategyMapManagementService.php';
require_once 'services/initiative/InitiativeManagementService.php';
require_once 'services/ubt/UnitBreakthroughManagementService.php';
require_once 'services/ubt/CommitmentManagementService.php';
require_once 'services/reports/IpReportService.php';

#service-implementations
require_once 'services/commons/DepartmentServiceSimpleImpl.php';
require_once 'services/commons/PositionServiceSimpleImpl.php';
require_once 'services/commons/UnitOfMeasureSimpleImpl.php';
require_once 'services/commons/RevisionHistoryLoggingServiceImpl.php';
require_once 'services/uam/SimpleUserManagementServiceImpl.php';
require_once 'services/indicator/IndicatorManagementServiceSimpleImpl.php';
require_once 'services/indicator/ScorecardManagementServiceSimpleImpl.php';
require_once 'services/map/StrategyMapManagementServiceSimpleImpl.php';
require_once 'services/initiative/InitiativeManagementServiceSimpleImpl.php';
require_once 'services/ubt/UnitBreakthroughManagementServiceSimpleImpl.php';
require_once 'services/ubt/CommitmentManagementServiceSimpleImpl.php';
require_once 'services/reports/IpReportServiceImpl.php';

session_start();
ob_start();
