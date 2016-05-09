ALTER TABLE `#__cot_admin` CHANGE `observation_number` `observation_number`  VARCHAR(100)  NOT NULL;
ALTER TABLE `#__cot_admin` DROP COLUMN `observation_list`;
ALTER TABLE `#__cot_admin` CHANGE `observation_culled` `observation_culled`  VARCHAR(100)  NOT NULL;
RENAME TABLE `#__tabu_admin` to `#__aquaculture_admin`;
ALTER TABLE `#__aquaculture_admin` DROP COLUMN `observation_state`;
ALTER TABLE `#__aquaculture_admin` DROP COLUMN `observation_method`;
ALTER TABLE `#__aquaculture_admin` DROP COLUMN `depth_range`;

CREATE TABLE IF NOT EXISTS `#__oi_admin` (
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

CREATE TABLE IF NOT EXISTS `#__mammals_admin` (
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
`depth_range` VARCHAR(100)  NOT NULL,
`observation_target` VARCHAR(100)  NOT NULL,
`observation_results` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`created_by` INT(11)  NOT NULL ,
`localisation` POINT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TRIGGER `#__trig_oi_admin_insert` BEFORE INSERT ON `#__oi_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));

CREATE TRIGGER `#__trig_oi_admin_update` BEFORE UPDATE ON `#__oi_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));

CREATE TRIGGER `#__trig_mammals_admin_insert` BEFORE INSERT ON `#__mammals_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));

CREATE TRIGGER `#__trig_mammals_admin_update` BEFORE UPDATE ON `#__mammals_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));

