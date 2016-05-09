ALTER TABLE `#__cot_admin` ADD COLUMN `observation_culled` INT(11)  NOT NULL;
ALTER TABLE `#__cot_public` ADD COLUMN `observation_culled` VARCHAR(100)  NOT NULL;

CREATE TABLE IF NOT EXISTS `#__sc_admin` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`observer_name` VARCHAR(100)  NOT NULL ,
`observation_date` VARCHAR(100) NOT NULL ,
`observation_location` TEXT NOT NULL ,
`observation_localisation` VARCHAR(100) NOT NULL ,
`observation_region` VARCHAR(100) NOT NULL ,
`observation_country` VARCHAR(100) NOT NULL ,
`observation_country_code` VARCHAR(100) NOT NULL ,
`observation_latitude` VARCHAR(100) NOT NULL ,
`observation_longitude` VARCHAR(100) NOT NULL ,
`observation_list` VARCHAR(100)  NOT NULL ,
`observation_state` VARCHAR(100) NOT NULL ,
`depth_range` VARCHAR(100)  NOT NULL,
`observation_method` VARCHAR(100)  NOT NULL,
`observation_target` VARCHAR(100)  NOT NULL,
`observation_results` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`created_by` INT(11)  NOT NULL ,
`localisation` POINT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__tabu_admin` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`observer_name` VARCHAR(100)  NOT NULL ,
`observation_date` VARCHAR(100) NOT NULL ,
`observation_location` TEXT NOT NULL ,
`observation_localisation` VARCHAR(100) NOT NULL ,
`observation_region` VARCHAR(100) NOT NULL ,
`observation_country` VARCHAR(100) NOT NULL ,
`observation_country_code` VARCHAR(100) NOT NULL ,
`observation_latitude` VARCHAR(100) NOT NULL ,
`observation_longitude` VARCHAR(100) NOT NULL ,
`observation_state` VARCHAR(100) NOT NULL ,
`depth_range` VARCHAR(100)  NOT NULL,
`observation_method` VARCHAR(100)  NOT NULL,
`observation_target` VARCHAR(100)  NOT NULL,
`observation_results` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`created_by` INT(11)  NOT NULL ,
`localisation` POINT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__cbm_admin` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`observer_name` VARCHAR(100)  NOT NULL ,
`observation_date` VARCHAR(100) NOT NULL ,
`observation_location` TEXT NOT NULL ,
`observation_localisation` VARCHAR(100) NOT NULL ,
`observation_region` VARCHAR(100) NOT NULL ,
`observation_country` VARCHAR(100) NOT NULL ,
`observation_country_code` VARCHAR(100) NOT NULL ,
`observation_latitude` VARCHAR(100) NOT NULL ,
`observation_longitude` VARCHAR(100) NOT NULL ,
`observation_results` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`created_by` INT(11)  NOT NULL ,
`localisation` POINT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__habitat_admin` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`observer_name` VARCHAR(100)  NOT NULL ,
`observation_date` VARCHAR(100) NOT NULL ,
`observation_location` TEXT NOT NULL ,
`observation_localisation` VARCHAR(100) NOT NULL ,
`observation_region` VARCHAR(100) NOT NULL ,
`observation_country` VARCHAR(100) NOT NULL ,
`observation_country_code` VARCHAR(100) NOT NULL ,
`observation_latitude` VARCHAR(100) NOT NULL ,
`observation_longitude` VARCHAR(100) NOT NULL ,
`observation_state` VARCHAR(100) NOT NULL ,
`depth_range` VARCHAR(100)  NOT NULL,
`observation_method` VARCHAR(100)  NOT NULL,
`observation_results` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`created_by` INT(11)  NOT NULL ,
`localisation` POINT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

