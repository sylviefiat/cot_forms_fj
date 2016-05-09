CREATE TABLE IF NOT EXISTS `#__cot_admin` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`observer_name` VARCHAR(100)  NOT NULL ,
`observer_tel` VARCHAR(100)  NOT NULL ,
`observer_email` VARCHAR(100)  NOT NULL ,
`observation_date` VARCHAR(100) NOT NULL ,
`observation_location` TEXT NOT NULL ,
`observation_localisation` VARCHAR(100) NOT NULL ,
`observation_region` VARCHAR(100) NOT NULL ,
`observation_country` VARCHAR(100) NOT NULL ,
`observation_country_code` VARCHAR(100) NOT NULL ,
`observation_latitude` VARCHAR(100) NOT NULL ,
`observation_longitude` VARCHAR(100) NOT NULL ,
`observation_number` VARCHAR(100)  NOT NULL ,
`observation_culled` INT(11)  NOT NULL ,
`observation_state` VARCHAR(100) NOT NULL ,
`counting_method_timed_swim` VARCHAR(100)  NOT NULL,
`counting_method_distance_swim` VARCHAR(100)  NOT NULL,
`counting_method_other` VARCHAR(100)  NOT NULL,
`depth_range` VARCHAR(100)  NOT NULL,
`observation_method` VARCHAR(100)  NOT NULL,
`remarks` TEXT NOT NULL,
`localisation` POINT NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`admin_validation` BOOLEAN NOT NULL default 0,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


CREATE TRIGGER `#__trig_cot_admin_insert` BEFORE INSERT ON `#__cot_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));

CREATE TRIGGER `#__trig_cot_admin_update` BEFORE UPDATE ON `#__cot_admin`
FOR EACH ROW SET NEW.localisation = GeomFromText( CONCAT('POINT(', NEW.observation_longitude, ' ', NEW.observation_latitude, ')' ));
