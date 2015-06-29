-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for capstone
DROP DATABASE IF EXISTS `capstone`;
CREATE DATABASE IF NOT EXISTS `capstone` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `capstone`;


-- Dumping structure for table capstone.commitments_main
DROP TABLE IF EXISTS `commitments_main`;
CREATE TABLE IF NOT EXISTS `commitments_main` (
  `commit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_ref` int(10) NOT NULL,
  `wig_ref` bigint(20) unsigned NOT NULL,
  `commit_description` tinytext NOT NULL,
  `commit_target` int(11) DEFAULT NULL,
  `status` varchar(5) NOT NULL DEFAULT 'P',
  PRIMARY KEY (`commit_id`),
  KEY `FK_commitments_main_ubt_wig` (`wig_ref`),
  KEY `FK_commitments_main_user_main` (`user_ref`),
  CONSTRAINT `FK_commitments_main_ubt_wig` FOREIGN KEY (`wig_ref`) REFERENCES `ubt_wig` (`wig_id`),
  CONSTRAINT `FK_commitments_main_user_main` FOREIGN KEY (`user_ref`) REFERENCES `user_main` (`umain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.commitments_movement
DROP TABLE IF EXISTS `commitments_movement`;
CREATE TABLE IF NOT EXISTS `commitments_movement` (
  `commit_ref` bigint(20) unsigned NOT NULL,
  `figure` varchar(50) DEFAULT NULL,
  `notes` tinytext NOT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_commitments_movement_commitments_main` (`commit_ref`),
  CONSTRAINT `FK_commitments_movement_commitments_main` FOREIGN KEY (`commit_ref`) REFERENCES `commitments_main` (`commit_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `dept_id` int(10) NOT NULL AUTO_INCREMENT,
  `dept_code` varchar(20) NOT NULL,
  `dept_name` tinytext NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.employees
DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `emp_id` int(10) NOT NULL,
  `emp_lname` varchar(50) NOT NULL,
  `emp_fname` varchar(50) NOT NULL,
  `emp_mname` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `position` int(10) DEFAULT NULL,
  `main_dept` int(10) DEFAULT NULL,
  `emp_stat` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`emp_id`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_employees_departments` (`main_dept`),
  KEY `FK_employees_positions` (`position`),
  CONSTRAINT `FK_employees_departments` FOREIGN KEY (`main_dept`) REFERENCES `departments` (`dept_id`),
  CONSTRAINT `FK_employees_positions` FOREIGN KEY (`position`) REFERENCES `positions` (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.indicators
DROP TABLE IF EXISTS `indicators`;
CREATE TABLE IF NOT EXISTS `indicators` (
  `indicator_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `indicator_description` tinytext NOT NULL,
  `indicator_rationale` tinytext,
  `formula_description` tinytext,
  `data_src` tinytext,
  `data_src_stat` varchar(10) DEFAULT NULL,
  `data_src_avail_date` tinytext,
  `uom` int(10) unsigned NOT NULL,
  `notes` text,
  PRIMARY KEY (`indicator_id`),
  KEY `FK_indicators_uom` (`uom`),
  CONSTRAINT `FK_indicators_uom` FOREIGN KEY (`uom`) REFERENCES `uom` (`uom_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.indicators_baseline
DROP TABLE IF EXISTS `indicators_baseline`;
CREATE TABLE IF NOT EXISTS `indicators_baseline` (
  `baseline_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator_ref` int(10) unsigned NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `period_year` year(4) DEFAULT NULL,
  `figure_value` tinytext,
  `notes` tinytext,
  PRIMARY KEY (`baseline_id`),
  KEY `FK_indicators_baseline_indicators` (`indicator_ref`),
  CONSTRAINT `FK_indicators_baseline_indicators` FOREIGN KEY (`indicator_ref`) REFERENCES `indicators` (`indicator_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_activities
DROP TABLE IF EXISTS `ini_activities`;
CREATE TABLE IF NOT EXISTS `ini_activities` (
  `activity_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_number` varchar(50) NOT NULL,
  `component_ref` int(10) unsigned DEFAULT NULL,
  `activity_desc` text,
  `target_desc` text NOT NULL,
  `target_figure` text,
  `indicator` text,
  `budget_figure` decimal(10,2) DEFAULT NULL,
  `source` text NOT NULL,
  `owners` text NOT NULL,
  `period_start_date` date NOT NULL,
  `period_end_date` date NOT NULL,
  `activity_status` varchar(5) NOT NULL DEFAULT 'P',
  PRIMARY KEY (`activity_id`),
  KEY `FK_ini_activities_ini_components` (`component_ref`),
  CONSTRAINT `FK_ini_activities_ini_components` FOREIGN KEY (`component_ref`) REFERENCES `ini_components` (`component_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_components
DROP TABLE IF EXISTS `ini_components`;
CREATE TABLE IF NOT EXISTS `ini_components` (
  `component_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `component_desc` text,
  `phase_ref` int(10) DEFAULT NULL,
  PRIMARY KEY (`component_id`),
  KEY `FK_ini_compmain_ini_phases` (`phase_ref`),
  CONSTRAINT `FK_ini_components_ini_phases` FOREIGN KEY (`phase_ref`) REFERENCES `ini_phases` (`phase_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_indicator_mapping
DROP TABLE IF EXISTS `ini_indicator_mapping`;
CREATE TABLE IF NOT EXISTS `ini_indicator_mapping` (
  `ini_ref` int(10) unsigned NOT NULL,
  `mp_ref` int(10) unsigned DEFAULT NULL,
  KEY `FK_ini_indicator_mapping_ini_main` (`ini_ref`),
  KEY `FK_ini_indicator_mapping_mp_main` (`mp_ref`),
  CONSTRAINT `FK_ini_indicator_mapping_ini_main` FOREIGN KEY (`ini_ref`) REFERENCES `ini_main` (`ini_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_ini_indicator_mapping_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_main
DROP TABLE IF EXISTS `ini_main`;
CREATE TABLE IF NOT EXISTS `ini_main` (
  `ini_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ini_name` text NOT NULL,
  `ini_desc` text NOT NULL,
  `ini_benf` text NOT NULL,
  `map_ref` int(10) NOT NULL,
  `period_start_date` varchar(50) NOT NULL,
  `period_end_date` varchar(50) NOT NULL,
  `eo_num` varchar(50) DEFAULT NULL,
  `ini_advisers` tinytext,
  `appr_stat` tinyint(4) NOT NULL DEFAULT '1',
  `aip_stat` tinyint(4) NOT NULL DEFAULT '1',
  `owner_stat` tinyint(4) NOT NULL DEFAULT '1',
  `organized_stat` tinyint(4) NOT NULL DEFAULT '1',
  `budget_stat` tinyint(4) NOT NULL DEFAULT '1',
  `report_mechanism` varchar(50) DEFAULT 'MONTHLY',
  `ini_stat` varchar(5) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`ini_id`),
  KEY `FK_ini_main_smap_main` (`map_ref`),
  CONSTRAINT `FK_ini_main_smap_main` FOREIGN KEY (`map_ref`) REFERENCES `smap_main` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_movement
DROP TABLE IF EXISTS `ini_movement`;
CREATE TABLE IF NOT EXISTS `ini_movement` (
  `activity_ref` bigint(20) unsigned NOT NULL,
  `user_ref` int(10) NOT NULL,
  `period_date` date DEFAULT NULL,
  `actual_figure` varchar(50) DEFAULT NULL,
  `budget_amount` decimal(10,2) DEFAULT NULL,
  `movement_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` tinytext NOT NULL,
  KEY `FK_ini_movement_ini_activities` (`activity_ref`),
  KEY `FK_ini_movement_user_main` (`user_ref`),
  CONSTRAINT `FK_ini_movement_ini_activities` FOREIGN KEY (`activity_ref`) REFERENCES `ini_activities` (`activity_id`),
  CONSTRAINT `FK_ini_movement_user_main` FOREIGN KEY (`user_ref`) REFERENCES `user_main` (`umain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_objective_mapping
DROP TABLE IF EXISTS `ini_objective_mapping`;
CREATE TABLE IF NOT EXISTS `ini_objective_mapping` (
  `ini_ref` int(11) unsigned NOT NULL,
  `obj_ref` int(11) unsigned DEFAULT NULL,
  KEY `FK_ini_objective_mapping_ini_main` (`ini_ref`),
  KEY `FK_ini_objective_mapping_smap_objectives` (`obj_ref`),
  CONSTRAINT `FK_ini_objective_mapping_ini_main` FOREIGN KEY (`ini_ref`) REFERENCES `ini_main` (`ini_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_ini_objective_mapping_smap_objectives` FOREIGN KEY (`obj_ref`) REFERENCES `smap_objectives` (`obj_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_phases
DROP TABLE IF EXISTS `ini_phases`;
CREATE TABLE IF NOT EXISTS `ini_phases` (
  `phase_id` int(10) NOT NULL AUTO_INCREMENT,
  `phase_number` tinyint(4) NOT NULL,
  `ini_ref` int(10) unsigned NOT NULL,
  `phase_title` tinytext NOT NULL,
  `phase_desc` text NOT NULL,
  PRIMARY KEY (`phase_id`),
  KEY `FK_ini_phases_ini_main` (`ini_ref`),
  CONSTRAINT `FK_ini_phases_ini_main` FOREIGN KEY (`ini_ref`) REFERENCES `ini_main` (`ini_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ini_teams
DROP TABLE IF EXISTS `ini_teams`;
CREATE TABLE IF NOT EXISTS `ini_teams` (
  `team_id` int(10) NOT NULL AUTO_INCREMENT,
  `ini_ref` int(10) unsigned NOT NULL,
  `dept_ref` int(10) NOT NULL,
  `team_type` varchar(20) NOT NULL,
  PRIMARY KEY (`team_id`),
  KEY `FK_ini_teams_ini_main` (`ini_ref`),
  KEY `FK_ini_teams_departments` (`dept_ref`),
  CONSTRAINT `FK_ini_teams_departments` FOREIGN KEY (`dept_ref`) REFERENCES `departments` (`dept_id`),
  CONSTRAINT `FK_ini_teams_ini_main` FOREIGN KEY (`ini_ref`) REFERENCES `ini_main` (`ini_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.lm_main
DROP TABLE IF EXISTS `lm_main`;
CREATE TABLE IF NOT EXISTS `lm_main` (
  `lm_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lm_desc` text NOT NULL,
  `lm_target` varchar(50) DEFAULT NULL,
  `ubt_ref` int(10) DEFAULT NULL,
  `uom_ref` int(10) unsigned DEFAULT NULL,
  `period_start_date` date NOT NULL,
  `period_end_date` date NOT NULL,
  `lm_designation` tinyint(4) NOT NULL,
  `lm_status` varchar(5) DEFAULT 'A',
  PRIMARY KEY (`lm_id`),
  KEY `FK_lm_main_ubt_main` (`ubt_ref`),
  KEY `FK_lm_main_uom` (`uom_ref`),
  CONSTRAINT `FK_lm_main_uom` FOREIGN KEY (`uom_ref`) REFERENCES `uom` (`uom_id`) ON DELETE CASCADE,
  CONSTRAINT `lm_main_ibfk_1` FOREIGN KEY (`ubt_ref`) REFERENCES `ubt_main` (`ubt_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.mp_main
DROP TABLE IF EXISTS `mp_main`;
CREATE TABLE IF NOT EXISTS `mp_main` (
  `mp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `obj_ref` int(10) unsigned DEFAULT NULL,
  `indicator_ref` int(10) unsigned DEFAULT NULL,
  `measure_type` varchar(5) NOT NULL,
  `mp_freq` varchar(20),
  `mp_stat` varchar(5) NOT NULL DEFAULT 'A',
  `period_start_date` date NOT NULL,
  `period_end_date` date NOT NULL,
  PRIMARY KEY (`mp_id`),
  KEY `FK_mp_main_smap_objectives` (`obj_ref`),
  KEY `FK_mp_main_indicators` (`indicator_ref`),
  CONSTRAINT `FK_mp_main_indicators` FOREIGN KEY (`indicator_ref`) REFERENCES `indicators` (`indicator_id`) ON DELETE SET NULL,
  CONSTRAINT `FK_mp_main_smap_objectives` FOREIGN KEY (`obj_ref`) REFERENCES `smap_objectives` (`obj_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.mp_movement
DROP TABLE IF EXISTS `mp_movement`;
CREATE TABLE IF NOT EXISTS `mp_movement` (
  `mp_ref` int(10) unsigned NOT NULL,
  `period_date` date NOT NULL,
  `movement_value` varchar(50) NOT NULL,
  KEY `FK_mp_movement_mp_main` (`mp_ref`),
  CONSTRAINT `FK_mp_movement_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.mp_movement_log
DROP TABLE IF EXISTS `mp_movement_log`;
CREATE TABLE IF NOT EXISTS `mp_movement_log` (
  `mp_ref` int(10) unsigned NOT NULL,
  `period_date` date NOT NULL,
  `user_ref` int(10) NOT NULL,
  `notes` text NOT NULL,
  `log_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_mp_movement_log_mp_main` (`mp_ref`),
  KEY `FK_mp_movement_log_user_main` (`user_ref`),
  CONSTRAINT `FK_mp_movement_log_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`),
  CONSTRAINT `FK_mp_movement_log_user_main` FOREIGN KEY (`user_ref`) REFERENCES `user_main` (`umain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.mp_rc
DROP TABLE IF EXISTS `mp_rc`;
CREATE TABLE IF NOT EXISTS `mp_rc` (
  `mprc_id` int(10) NOT NULL AUTO_INCREMENT,
  `mp_ref` int(10) unsigned NOT NULL,
  `dept_ref` int(10) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`mprc_id`),
  KEY `FK_mp_rc_mp_main` (`mp_ref`),
  KEY `FK_mp_rc_departments` (`dept_ref`),
  CONSTRAINT `FK_mp_rc_departments` FOREIGN KEY (`dept_ref`) REFERENCES `departments` (`dept_id`),
  CONSTRAINT `FK_mp_rc_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.mp_targets
DROP TABLE IF EXISTS `mp_targets`;
CREATE TABLE IF NOT EXISTS `mp_targets` (
  `target_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mp_ref` int(11) unsigned NOT NULL,
  `data_group` tinytext,
  `covered_year` year(4) NOT NULL,
  `value` varchar(50) NOT NULL,
  `notes` tinytext,
  PRIMARY KEY (`target_id`),
  KEY `FK_mp_targets_mp_main` (`mp_ref`),
  CONSTRAINT `FK_mp_targets_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.positions
DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `pos_id` int(10) NOT NULL AUTO_INCREMENT,
  `pos_desc` tinytext NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.rev_history
DROP TABLE IF EXISTS `rev_history`;
CREATE TABLE IF NOT EXISTS `rev_history` (
  `module_code` varchar(10) NOT NULL,
  `module_id` varchar(50) NOT NULL,
  `user_ref` int(10) DEFAULT NULL,
  `notes` text NOT NULL,
  `rev_type` varchar(5) NOT NULL,
  `rev_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_rev_history_employees` (`user_ref`),
  CONSTRAINT `FK_rev_history_employees` FOREIGN KEY (`user_ref`) REFERENCES `employees` (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.smap_main
DROP TABLE IF EXISTS `smap_main`;
CREATE TABLE IF NOT EXISTS `smap_main` (
  `map_id` int(10) NOT NULL AUTO_INCREMENT,
  `map_desc` tinytext NOT NULL,
  `map_vision` text NOT NULL,
  `map_mission` text,
  `map_values` text,
  `map_type` varchar(5) NOT NULL DEFAULT 'LT',
  `period_date_start` date NOT NULL,
  `period_date_end` date NOT NULL,
  `map_stat` varchar(5) NOT NULL DEFAULT 'A',
  `readonly` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.smap_objectives
DROP TABLE IF EXISTS `smap_objectives`;
CREATE TABLE IF NOT EXISTS `smap_objectives` (
  `obj_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `map_ref` int(10) NOT NULL,
  `obj_desc` text NOT NULL,
  `shift_desc` text,
  `agenda_desc` text,
  `pers_ref` int(10) NOT NULL,
  `theme_ref` int(10) DEFAULT NULL,
  `sector_ref` int(10) DEFAULT NULL,
  `period_date_start` date NOT NULL,
  `period_date_end` date NOT NULL,
  `obj_stat` varchar(5) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`obj_id`),
  KEY `FK_smap_objectives_smap_main` (`map_ref`),
  KEY `FK_smap_objectives_smap_perspectives` (`pers_ref`),
  KEY `FK_smap_objectives_smap_themes` (`theme_ref`),
  KEY `sector_ref` (`sector_ref`),
  CONSTRAINT `FK_smap_objectives_smap_main` FOREIGN KEY (`map_ref`) REFERENCES `smap_main` (`map_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_smap_objectives_smap_perspectives` FOREIGN KEY (`pers_ref`) REFERENCES `smap_perspectives` (`pers_id`),
  CONSTRAINT `FK_smap_objectives_smap_themes` FOREIGN KEY (`theme_ref`) REFERENCES `smap_themes` (`theme_id`) ON DELETE SET NULL,
  CONSTRAINT `smap_objectives_ibfk_1` FOREIGN KEY (`sector_ref`) REFERENCES `sectors` (`sector_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.smap_perspectives
DROP TABLE IF EXISTS `smap_perspectives`;
CREATE TABLE IF NOT EXISTS `smap_perspectives` (
  `pers_id` int(10) NOT NULL AUTO_INCREMENT,
  `pers_desc` text NOT NULL,
  `pos_order` tinyint(4) NOT NULL,
  `map_ref` int(10) NOT NULL,
  PRIMARY KEY (`pers_id`),
  KEY `FK_smap_perspectives_smap_main` (`map_ref`),
  CONSTRAINT `FK_smap_perspectives_smap_main` FOREIGN KEY (`map_ref`) REFERENCES `smap_main` (`map_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.smap_themes
DROP TABLE IF EXISTS `smap_themes`;
CREATE TABLE IF NOT EXISTS `smap_themes` (
  `theme_id` int(10) NOT NULL AUTO_INCREMENT,
  `theme_desc` text NOT NULL,
  `map_ref` int(10) NOT NULL,
  PRIMARY KEY (`theme_id`),
  KEY `FK_smap_themes_smap_main` (`map_ref`),
  CONSTRAINT `FK_smap_themes_smap_main` FOREIGN KEY (`map_ref`) REFERENCES `smap_main` (`map_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ubt_indicator_mapping
DROP TABLE IF EXISTS `ubt_indicator_mapping`;
CREATE TABLE IF NOT EXISTS `ubt_indicator_mapping` (
  `ubt_ref` int(11) DEFAULT NULL,
  `mp_ref` int(11) unsigned DEFAULT NULL,
  KEY `FK_ubt_indicator_mapping_ubt_main` (`ubt_ref`),
  KEY `FK_ubt_indicator_mapping_mp_main` (`mp_ref`),
  CONSTRAINT `FK_ubt_indicator_mapping_mp_main` FOREIGN KEY (`mp_ref`) REFERENCES `mp_main` (`mp_id`),
  CONSTRAINT `FK_ubt_indicator_mapping_ubt_main` FOREIGN KEY (`ubt_ref`) REFERENCES `ubt_main` (`ubt_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ubt_main
DROP TABLE IF EXISTS `ubt_main`;
CREATE TABLE IF NOT EXISTS `ubt_main` (
  `ubt_id` int(10) NOT NULL AUTO_INCREMENT,
  `map_ref` int(10) NOT NULL,
  `dept_ref` int(10) DEFAULT NULL,
  `ubt_stmt` text NOT NULL,
  `baseline_figure` varchar(50) DEFAULT NULL,
  `target_figure` varchar(50) DEFAULT NULL,
  `uom_ref` int(10) unsigned DEFAULT NULL,
  `period_date_start` date DEFAULT NULL,
  `period_date_end` date DEFAULT NULL,
  `ubt_stat` varchar(5) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`ubt_id`),
  KEY `FK_ubt_main_departments` (`dept_ref`),
  KEY `FK_ubt_main_smap_main` (`map_ref`),
  KEY `FK_ubt_main_uom` (`uom_ref`),
  CONSTRAINT `FK_ubt_main_departments` FOREIGN KEY (`dept_ref`) REFERENCES `departments` (`dept_id`) ON DELETE SET NULL,
  CONSTRAINT `FK_ubt_main_smap_main` FOREIGN KEY (`map_ref`) REFERENCES `smap_main` (`map_id`),
  CONSTRAINT `FK_ubt_main_uom` FOREIGN KEY (`uom_ref`) REFERENCES `uom` (`uom_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ubt_movement
DROP TABLE IF EXISTS `ubt_movement`;
CREATE TABLE IF NOT EXISTS `ubt_movement` (
  `wig_ref` bigint(20) unsigned NOT NULL,
  `user_ref` int(10) DEFAULT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ubt_figure` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lm1_figure` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `lm2_figure` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `notes` varchar(50) COLLATE utf8_bin NOT NULL,
  KEY `FK_ubt_movement_ubt_wig` (`wig_ref`),
  KEY `FK_ubt_movement_user_main` (`user_ref`),
  CONSTRAINT `FK_ubt_movement_ubt_wig` FOREIGN KEY (`wig_ref`) REFERENCES `ubt_wig` (`wig_id`),
  CONSTRAINT `FK_ubt_movement_user_main` FOREIGN KEY (`user_ref`) REFERENCES `user_main` (`umain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ubt_objective_mapping
DROP TABLE IF EXISTS `ubt_objective_mapping`;
CREATE TABLE IF NOT EXISTS `ubt_objective_mapping` (
  `ubt_ref` int(11) NOT NULL,
  `obj_ref` int(11) unsigned NOT NULL,
  KEY `FK_ubt_objective_mapping_ubt_main` (`ubt_ref`),
  KEY `FK_ubt_objective_mapping_smap_objectives` (`obj_ref`),
  CONSTRAINT `FK_ubt_objective_mapping_smap_objectives` FOREIGN KEY (`obj_ref`) REFERENCES `smap_objectives` (`obj_id`),
  CONSTRAINT `FK_ubt_objective_mapping_ubt_main` FOREIGN KEY (`ubt_ref`) REFERENCES `ubt_main` (`ubt_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.ubt_wig
DROP TABLE IF EXISTS `ubt_wig`;
CREATE TABLE IF NOT EXISTS `ubt_wig` (
  `wig_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ubt_ref` int(11) NOT NULL,
  `period_start_date` date NOT NULL,
  `period_end_date` date NOT NULL,
  `actual_start_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `meeting_venue` text,
  `meeting_date` date DEFAULT NULL,
  `meeting_time_start` time DEFAULT NULL,
  `meeting_time_end` time DEFAULT NULL,
  `status` varchar(5) NOT NULL DEFAULT 'O',
  PRIMARY KEY (`wig_id`),
  KEY `FK_ubt_wig_ubt_main` (`ubt_ref`),
  CONSTRAINT `FK_ubt_wig_ubt_main` FOREIGN KEY (`ubt_ref`) REFERENCES `ubt_main` (`ubt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.uom
DROP TABLE IF EXISTS `uom`;
CREATE TABLE IF NOT EXISTS `uom` (
  `uom_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uom_symbol` varchar(20) DEFAULT NULL,
  `uom_desc` tinytext NOT NULL,
  PRIMARY KEY (`uom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.user_actions
DROP TABLE IF EXISTS `user_actions`;
CREATE TABLE IF NOT EXISTS `user_actions` (
  `module_code` varchar(5) NOT NULL,
  `actions` text NOT NULL,
  `type_ref` int(10) NOT NULL,
  KEY `FK_user_actions_user_types` (`type_ref`),
  CONSTRAINT `FK_user_actions_user_types` FOREIGN KEY (`type_ref`) REFERENCES `user_types` (`utype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.user_main
DROP TABLE IF EXISTS `user_main`;
CREATE TABLE IF NOT EXISTS `user_main` (
  `umain_id` int(10) NOT NULL AUTO_INCREMENT,
  `emp_ref` int(10) NOT NULL,
  `type_ref` int(10) NOT NULL,
  `dept_ref` int(10) NOT NULL,
  `pos_ref` int(11) DEFAULT NULL,
  PRIMARY KEY (`umain_id`),
  KEY `FK_user_main_employees` (`emp_ref`),
  KEY `FK_user_main_user_types` (`type_ref`),
  KEY `FK_user_main_departments` (`dept_ref`),
  CONSTRAINT `FK_user_main_departments` FOREIGN KEY (`dept_ref`) REFERENCES `departments` (`dept_id`),
  CONSTRAINT `FK_user_main_employees` FOREIGN KEY (`emp_ref`) REFERENCES `employees` (`emp_id`),
  CONSTRAINT `FK_user_main_user_types` FOREIGN KEY (`type_ref`) REFERENCES `user_types` (`utype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table capstone.user_types
DROP TABLE IF EXISTS `user_types`;
CREATE TABLE IF NOT EXISTS `user_types` (
  `utype_id` int(10) NOT NULL AUTO_INCREMENT,
  `type_name` tinytext,
  `type_desc` tinytext NOT NULL,
  PRIMARY KEY (`utype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
